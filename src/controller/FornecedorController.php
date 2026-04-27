<?php

namespace controller;

use Exception;
use dao\FornecedorDAO;
use dao\ProdutoDAO;
use model\Fornecedor;

class FornecedorController
{
    public function listar()
    {
        try {
            $fornecedores = FornecedorDAO::listar();
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
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

    public function novo()
    {
        $fornecedor   = new Fornecedor();
        $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
        $paginaAtiva  = 'fornecedores';
        $tituloPagina = 'Novo Fornecedor';
        require __DIR__ . '/../view/cadastro-fornecedor.php';
    }

    public function buscar(array $params)
    {
        try {
            $fornecedor = FornecedorDAO::buscarPorId($params['id']);
            if (empty($fornecedor)) throw new Exception('Fornecedor não encontrado.');
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
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

            if (empty($nome)) throw new Exception('O nome do fornecedor é obrigatório.');

            // Valida CNPJ (apenas dígitos, 14 chars)
            $cnpjLimpo = preg_replace('/\D/', '', $cnpj ?? '');
            if (!empty($cnpjLimpo) && strlen($cnpjLimpo) !== 14) {
                throw new Exception('CNPJ inválido. Informe 14 dígitos.');
            }

            $fornecedor = $id ? FornecedorDAO::buscarPorId($id) : new Fornecedor();
            if (empty($fornecedor)) throw new Exception('Fornecedor não encontrado.');

            $fornecedor->setNome($nome);
            $fornecedor->setCnpj($cnpj ?? '');
            $fornecedor->setEmail($email ?? '');
            $fornecedor->setTelefone($telefone ?? '');
            $fornecedor->setAtivo($ativo ?? true);

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
