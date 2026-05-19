<?php

namespace controller;

use Exception;
use dao\FornecedorDAO;
use dao\ProdutoDAO;
use model\Fornecedor;
use dao\EmpresaDAO;

class FornecedorController
{
    public function novo()
    {
        return $this->cadastrar();
    }

    public function listar()
    {
        try {
            $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
            $fornecedores = $empresaId ? FornecedorDAO::listarPorEmpresa($empresaId) : [];
            $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        } catch (Exception $ex) {
            $fornecedores = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'fornecedores';
            $tituloPagina = 'Fornecedores';
            require __DIR__ . '/../view/lista-fornecedores.php';
        }
    }

    public function cadastrar()
    {
        $fornecedor   = new Fornecedor();
        $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
        $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        $paginaAtiva  = 'fornecedores';
        $tituloPagina = 'Novo Fornecedor';
        require __DIR__ . '/../view/cadastro-fornecedor.php';
    }

    public function buscar(array $params)
    {
        try {
            $empresaId  = $_SESSION['usuario']['empresaId'] ?? null;
            $fornecedor = $empresaId ? FornecedorDAO::buscarPorIdEEmpresa($params['id'], $empresaId) : null;
            if (empty($fornecedor)) throw new Exception('Fornecedor não encontrado.');
            $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/fornecedores');
            exit;
        } finally {
            if (isset($fornecedor) && $fornecedor) {
                $paginaAtiva  = 'fornecedores';
                $tituloPagina = 'Editar Fornecedor';
                require __DIR__ . '/../view/cadastro-fornecedor.php';
            }
        }
    }

    public function salvar(array $params)
    {
        try {
            $id       = $params['id'] ?? null;
            $nome     = filter_input(INPUT_POST, 'nome',     FILTER_SANITIZE_SPECIAL_CHARS);
            $cnpj     = filter_input(INPUT_POST, 'cnpj',     FILTER_SANITIZE_SPECIAL_CHARS);
            $email    = filter_input(INPUT_POST, 'email',    FILTER_SANITIZE_EMAIL);
            $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
            $ativo    = filter_input(INPUT_POST, 'ativo',    FILTER_VALIDATE_BOOLEAN);
            $empresaId = $_SESSION['usuario']['empresaId'] ?? null;

            if (empty($nome)) throw new Exception('O nome do fornecedor é obrigatório.');

            // Valida CNPJ (apenas dígitos, 14 chars)
            $cnpjLimpo = preg_replace('/\D/', '', $cnpj ?? '');
            if (!empty($cnpjLimpo) && strlen($cnpjLimpo) !== 14) {
                throw new Exception('CNPJ inválido. Informe 14 dígitos.');
            }

            $fornecedor = $id && $empresaId
                ? FornecedorDAO::buscarPorIdEEmpresa($id, $empresaId)
                : new Fornecedor();
            if (empty($fornecedor)) throw new Exception('Fornecedor não encontrado.');

            $fornecedor->setNome($nome);
            $fornecedor->setCnpj($cnpj ?? '');
            $fornecedor->setEmail($email ?? '');
            $fornecedor->setTelefone($telefone ?? '');
            $fornecedor->setAtivo($ativo ?? true);

            if ($empresaId && !$fornecedor->getEmpresa()) {
                $empresa = EmpresaDAO::buscarPorId($empresaId);
                if (!$empresa) throw new Exception('Empresa inválida.');
                $fornecedor->setEmpresa($empresa);
            }

            FornecedorDAO::salvar($fornecedor);
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Fornecedor salvo com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/fornecedores');
            exit;
        }
    }

    public function deletar(array $params)
    {
        try {
            $resultado = FornecedorDAO::deletar($params['id']);
            if (!$resultado) throw new Exception('Fornecedor não encontrado.');
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Fornecedor removido com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/fornecedores');
            exit;
        }
    }
}
