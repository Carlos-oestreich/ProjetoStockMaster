<?php

namespace controller;

use Exception;
use dao\CategoriaDAO;
use model\Categoria;

class CategoriaController
{
    public function listar()
    {
        try {
            $categorias   = CategoriaDAO::listar();
            $totalAlertas = count(\dao\ProdutoDAO::listarEstoqueBaixo());
        } catch (Exception $ex) {
            $categorias = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'categorias';
            $tituloPagina = 'Categorias';
            require __DIR__ . '/../view/lista-categorias.php';
        }
    }

    public function novo()
    {
        $categoria    = new Categoria();
        $totalAlertas = count(\dao\ProdutoDAO::listarEstoqueBaixo());
        $paginaAtiva  = 'categorias';
        $tituloPagina = 'Nova Categoria';
        require __DIR__ . '/../view/cadastro-categoria.php';
    }

    public function buscar(array $params)
    {
        try {
            $categoria = CategoriaDAO::buscarPorId($params['id']);
            if (empty($categoria)) throw new Exception('Categoria não encontrada.');
            $totalAlertas = count(\dao\ProdutoDAO::listarEstoqueBaixo());
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/categorias');
            exit;
        } finally {
            if (isset($categoria) && $categoria) {
                $paginaAtiva  = 'categorias';
                $tituloPagina = 'Editar Categoria';
                require __DIR__ . '/../view/cadastro-categoria.php';
            }
        }
    }

    public function salvar(array $params)
    {
        try {
            $id             = $params['id'] ?? null;
            $nome           = filter_input(INPUT_POST, 'nome',           FILTER_SANITIZE_SPECIAL_CHARS);
            $descricao      = filter_input(INPUT_POST, 'descricao',      FILTER_SANITIZE_SPECIAL_CHARS);
            $setor          = filter_input(INPUT_POST, 'setor',          FILTER_SANITIZE_SPECIAL_CHARS);
            $codigoInterno  = filter_input(INPUT_POST, 'codigo_interno', FILTER_SANITIZE_SPECIAL_CHARS);
            $ativo          = filter_input(INPUT_POST, 'ativo',          FILTER_VALIDATE_BOOLEAN);

            if (empty($nome)) throw new Exception('O nome da categoria é obrigatório.');

            $categoria = $id ? CategoriaDAO::buscarPorId($id) : new Categoria();
            if (empty($categoria)) throw new Exception('Categoria não encontrada.');

            $categoria->setNome($nome);
            $categoria->setDescricao($descricao ?? '');
            $categoria->setSetor($setor ?? '');
            $categoria->setCodigoInterno($codigoInterno ?? '');
            $categoria->setAtivo($ativo ?? true);

            CategoriaDAO::salvar($categoria);
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Categoria salva com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/categorias');
            exit;
        }
    }

    public function deletar(array $params)
    {
        try {
            $resultado = CategoriaDAO::deletar($params['id']);
            if (!$resultado) throw new Exception('Categoria não encontrada.');
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Categoria removida com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/categorias');
            exit;
        }
    }
}