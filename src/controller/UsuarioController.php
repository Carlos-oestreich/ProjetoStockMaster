<?php

namespace controller;

use Exception;
use dao\UsuarioDAO;
use dao\ProdutoDAO;
use model\Usuario;

class UsuarioController
{
    public function listar()
    {
        try {
            $usuarios     = UsuarioDAO::listar();
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
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

    public function novo()
    {
        $usuario      = new Usuario();
        $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
        $paginaAtiva  = 'usuarios';
        $tituloPagina = 'Novo Usuario';
        require __DIR__ . '/../view/cadastro-usuario.php';
    }

    public function buscar(array $params)
    {
        try {
            $usuario = UsuarioDAO::buscarPorId($params['id']);
            if (empty($usuario)) throw new Exception('Usuario nao encontrado.');
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        } finally {
            if (isset($usuario) && $usuario) {
                $paginaAtiva  = 'usuarios';
                $tituloPagina = 'Editar Usuario';
                require __DIR__ . '/../view/cadastro-usuario.php';
            }
        }
    }

    public function salvar(array $params)
    {
        try {
            $id        = $params['id'] ?? null;
            $nome      = filter_input(INPUT_POST, 'nome',      FILTER_SANITIZE_SPECIAL_CHARS);
            $email     = filter_input(INPUT_POST, 'email',     FILTER_SANITIZE_EMAIL);
            $matricula = filter_input(INPUT_POST, 'matricula', FILTER_SANITIZE_SPECIAL_CHARS);
            $perfil    = filter_input(INPUT_POST, 'perfil',    FILTER_SANITIZE_SPECIAL_CHARS);
            $senha     = filter_input(INPUT_POST, 'senha',     FILTER_SANITIZE_SPECIAL_CHARS);
            $ativo     = filter_input(INPUT_POST, 'ativo',     FILTER_VALIDATE_BOOLEAN);

            if (empty($nome) || empty($email)) {
                throw new Exception('Nome e e-mail sao obrigatorios.');
            }
            if (!in_array($perfil, ['ADM', 'OPERADOR'], true)) {
                throw new Exception('Perfil invalido.');
            }

            $usuario = $id ? UsuarioDAO::buscarPorId($id) : new Usuario();
            if (empty($usuario)) throw new Exception('Usuario nao encontrado.');

            $usuario->setNome($nome);
            $usuario->setEmail($email);
            $usuario->setMatricula($matricula ?? '');
            $usuario->setPerfil($perfil);
            $usuario->setAtivo($ativo ?? true);

            // Apenas atualiza senha se foi informada
            if (!empty($senha)) {
                if (strlen($senha) < 4) {
                    throw new Exception('A senha deve ter pelo menos 4 caracteres.');
                }
                $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
            } elseif (!$id) {
                throw new Exception('Informe a senha para o novo usuario.');
            }

            UsuarioDAO::salvar($usuario);
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Usuario salvo com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/usuarios');
            exit;
        }
    }

    public function deletar(array $params)
    {
        try {
            // Impede deletar o proprio usuario logado
            if ($params['id'] == ($_SESSION['usuario']['id'] ?? null)) {
                throw new Exception('Voce nao pode excluir seu proprio usuario.');
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