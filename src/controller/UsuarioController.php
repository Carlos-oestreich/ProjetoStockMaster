<?php

namespace controller;

use Exception;
use dao\UsuarioDAO;
use dao\ProdutoDAO;
use dao\EmpresaDAO;
use model\Usuario;

class UsuarioController
{
    public function novo()
    {
        return $this->cadastrar();
    }

    public function listar()
    {
        try {
            $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
            $usuarios     = $empresaId ? UsuarioDAO::listarPorEmpresa($empresaId) : [];
            $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        } catch (Exception $ex) {
            $usuarios = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'usuarios';
            $tituloPagina = 'Usuarios';
            require __DIR__ . '/../view/lista-usuarios.php';
        }
    }

    public function cadastrar()
    {
        $perfilLogado = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';
        $usuario      = new Usuario();
        $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
        $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        $paginaAtiva  = 'usuarios';
        $tituloPagina = 'Novo Usuario';
        $somenteOperador = $perfilLogado === 'ADM';
        require __DIR__ . '/../view/cadastro-usuarios.php';
    }

    public function buscar(array $params)
    {
        try {
            $perfilLogado = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';
            $empresaId = $_SESSION['usuario']['empresaId'] ?? null;
            $usuario = $empresaId ? UsuarioDAO::buscarPorIdEEmpresa($params['id'], $empresaId) : null;
            if (empty($usuario)) throw new Exception('Usuario nao encontrado.');
            if ($perfilLogado === 'ADM' && $usuario->getPerfil() !== 'OPERADOR') {
                throw new Exception('Administradores so podem editar operadores.');
            }
            $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        } finally {
            if (isset($usuario) && $usuario) {
                $paginaAtiva  = 'usuarios';
                $tituloPagina = 'Editar Usuario';
                $somenteOperador = $perfilLogado === 'ADM';
                require __DIR__ . '/../view/cadastro-usuarios.php';
            }
        }
    }

    public function salvar(array $params)
    {
        $id           = $params['id'] ?? null;
        $perfilLogado = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';
        $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
        $paginaAtiva  = 'usuarios';
        $tituloPagina = $id ? 'Editar Usuario' : 'Novo Usuario';
        $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        $somenteOperador = $perfilLogado === 'ADM';

        $usuario = $id && $empresaId
            ? UsuarioDAO::buscarPorIdEEmpresa($id, $empresaId)
            : new Usuario();

        if ($id && !$usuario) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => 'Usuario nao encontrado.'];
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        }

        try {
            $nome      = filter_input(INPUT_POST, 'nome',      FILTER_SANITIZE_SPECIAL_CHARS);
            $email     = filter_input(INPUT_POST, 'email',     FILTER_SANITIZE_EMAIL);
            $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_SPECIAL_CHARS);
            $perfil    = filter_input(INPUT_POST, 'perfil',    FILTER_SANITIZE_SPECIAL_CHARS);
            $senha     = filter_input(INPUT_POST, 'senha',     FILTER_SANITIZE_SPECIAL_CHARS);
            $ativo     = filter_input(INPUT_POST, 'ativo',     FILTER_VALIDATE_BOOLEAN);
            $cpf       = filter_input(INPUT_POST, 'cpf',       FILTER_SANITIZE_SPECIAL_CHARS);

            $usuario->setNome($nome);
            $usuario->setEmail($email);
            $usuario->setMatricula($matricula ?? '');
            $usuario->setPerfil($perfil);
            $usuario->setAtivo($ativo ?? true);

            $cpfLimpo = preg_replace('/\D/', '', $cpf ?? '');
            $usuario->setCpf($cpfLimpo ?: null);

            if (empty($nome) || empty($email)) {
                throw new Exception('Nome e e-mail sao obrigatorios.');
            }
            if (!in_array($perfil, ['ADM', 'OPERADOR'], true)) {
                throw new Exception('Perfil invalido.');
            }

            if ($perfilLogado === 'ADM' && $perfil !== 'OPERADOR') {
                throw new Exception('Administradores so podem cadastrar operadores.');
            }

            if ($perfilLogado === 'ADM' && $usuario->getId() !== null && $usuario->getPerfil() !== 'OPERADOR') {
                throw new Exception('Administradores so podem editar operadores.');
            }

            if (empty($cpfLimpo)) {
                throw new Exception('CPF e obrigatorio.');
            }
            if (strlen($cpfLimpo) !== 11) {
                throw new Exception('CPF invalido. Informe os 11 digitos.');
            }

            $usuarioEmail = $usuario->getEmail();
            if ($email && $email !== $usuarioEmail) {
                $existente = UsuarioDAO::buscarPorEmail($email);
                if ($existente && $existente->getId() !== $usuario->getId()) {
                    throw new Exception('Este e-mail ja esta cadastrado no sistema.');
                }
            }

            $existenteCpf = UsuarioDAO::buscarPorCpf($cpfLimpo);
            if ($existenteCpf && $existenteCpf->getId() !== $usuario->getId()) {
                throw new Exception('Este CPF ja esta cadastrado.');
            }

            if (!empty($matricula)) {
                $existenteMatricula = UsuarioDAO::buscarPorMatricula($matricula);
                if ($existenteMatricula && $existenteMatricula->getId() !== $usuario->getId()) {
                    throw new Exception('Esta matricula ja esta cadastrada.');
                }
            }

            // Apenas atualiza senha se foi informada
            if (!empty($senha)) {
                ConfiguracaoController::validarForcaSenha($senha);
                $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
                $usuario->setSenhaTemporaria(true);
            } elseif (!$id) {
                throw new Exception('Informe a senha para o novo usuario.');
            }

            if ($empresaId && !$usuario->getEmpresa()) {
                $empresa = EmpresaDAO::buscarPorId($empresaId);
                if (!$empresa) throw new Exception('Empresa invalida.');
                $usuario->setEmpresa($empresa);
            }
            if (!$id) {
                $usuario->setSenhaTemporaria(true);
            }

            UsuarioDAO::salvar($usuario);
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Usuario salvo com sucesso!'];
            header('Location: ' . BASE_URL . '/usuarios');
            exit;

        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            require __DIR__ . '/../view/cadastro-usuarios.php';
            return;
        }
    }

    public function deletar(array $params)
    {
        try {
            $perfilLogado = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';
            // Impede deletar o proprio usuario logado
            if ($params['id'] == ($_SESSION['usuario']['id'] ?? null)) {
                throw new Exception('Voce nao pode excluir seu proprio usuario.');
            }
            $empresaId = $_SESSION['usuario']['empresaId'] ?? null;
            $usuario = $empresaId ? UsuarioDAO::buscarPorIdEEmpresa($params['id'], $empresaId) : null;
            if (!$usuario) throw new Exception('Usuario nao encontrado.');
            if ($perfilLogado === 'ADM' && $usuario->getPerfil() !== 'OPERADOR') {
                throw new Exception('Administradores so podem excluir operadores.');
            }
            $resultado = UsuarioDAO::deletar($params['id']);
            if (!$resultado) throw new Exception('Usuario nao encontrado.');
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Usuario removido com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        }
    }
}