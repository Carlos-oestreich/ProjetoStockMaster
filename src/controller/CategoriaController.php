<?php

namespace controller;

use Exception;
use dao\CategoriaDAO;
use model\Categoria;
use dao\EmpresaDAO;

class CategoriaController
{
    public function novo()
    {
        return $this->cadastrar();
    }

    public function listar()
    {
        try {
            $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
            $categorias   = $empresaId ? CategoriaDAO::listarPorEmpresa($empresaId) : [];
            $totalAlertas = $empresaId ? count(\dao\ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
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

    public function cadastrar()
    {
        $categoria    = new Categoria();
        $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
        $totalAlertas = $empresaId ? count(\dao\ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        $paginaAtiva  = 'categorias';
        $tituloPagina = 'Nova Categoria';
        require __DIR__ . '/../view/cadastro-categoria.php';
    }

    public function buscar(array $params)
    {
        try {
            $empresaId = $_SESSION['usuario']['empresaId'] ?? null;
            $categoria = $empresaId ? CategoriaDAO::buscarPorIdEEmpresa($params['id'], $empresaId) : null;
            if (empty($categoria)) throw new Exception('Categoria não encontrada.');
            $totalAlertas = $empresaId ? count(\dao\ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
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
            $empresaId      = $_SESSION['usuario']['empresaId'] ?? null;

            if (empty($nome)) throw new Exception('O nome da categoria é obrigatório.');

            $categoria = $id && $empresaId
                ? CategoriaDAO::buscarPorIdEEmpresa($id, $empresaId)
                : new Categoria();
            if (empty($categoria)) throw new Exception('Categoria não encontrada.');

            $categoria->setNome($nome);
            $categoria->setDescricao($descricao ?? '');
            $categoria->setSetor($setor ?? '');
            $categoria->setCodigoInterno($codigoInterno ?? '');
            $categoria->setAtivo($ativo ?? true);

            if ($empresaId && !$categoria->getEmpresa()) {
                $empresa = EmpresaDAO::buscarPorId($empresaId);
                if (!$empresa) throw new Exception('Empresa inválida.');
                $categoria->setEmpresa($empresa);
            }

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