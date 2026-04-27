<?php

namespace controller;

use DateTime;
use Exception;
use dao\ProdutoDAO;
use dao\CategoriaDAO;
use dao\FornecedorDAO;
use dao\MovimentacaoEstoqueDAO;
use model\Produto;
use model\MovimentacoesEstoque;

class ProdutoController
{
    public function listar()
    {
        try {
            $produtos    = ProdutoDAO::listar();
            $categorias  = CategoriaDAO::listar();
            $fornecedores = FornecedorDAO::listar();
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
        } catch (Exception $ex) {
            $produtos = $categorias = $fornecedores = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'produtos';
            $tituloPagina = 'Produtos';
            require __DIR__ . '/../view/lista-produtos.php';
        }
    }

    public function novo()
    {
        try {
            $produto      = new Produto();
            $categorias   = CategoriaDAO::listar();
            $fornecedores = FornecedorDAO::listar();
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
        } catch (Exception $ex) {
            $produto = new Produto();
            $categorias = $fornecedores = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'produtos';
            $tituloPagina = 'Novo Produto';
            require __DIR__ . '/../view/cadastro-produto.php';
        }
    }

    public function buscar(array $params)
    {
        try {
            $produto = ProdutoDAO::buscarPorId($params['id']);
            if (empty($produto)) throw new Exception('Produto não encontrado.');
            $categorias   = CategoriaDAO::listar();
            $fornecedores = FornecedorDAO::listar();
            $totalAlertas = count(ProdutoDAO::listarEstoqueBaixo());
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/produtos');
            exit;
        } finally {
            if (isset($produto) && $produto) {
                $paginaAtiva  = 'produtos';
                $tituloPagina = 'Editar Produto';
                require __DIR__ . '/../view/cadastro-produto.php';
            }
        }
    }

    public function salvar(array $params)
    {
        try {
            $id    = $params['id'] ?? null;
            $perfil = $_SESSION['usuario']['perfil'] ?? 'OPERADOR';

            // Apenas admin pode cadastrar/editar produtos
            if ($perfil !== 'ADM') {
                throw new Exception('Acesso negado. Apenas administradores podem gerenciar produtos.');
            }

            // Sanitização com filter_input
            $sku              = filter_input(INPUT_POST, 'sku',              FILTER_SANITIZE_SPECIAL_CHARS);
            $nome             = filter_input(INPUT_POST, 'nome',             FILTER_SANITIZE_SPECIAL_CHARS);
            $descricao        = filter_input(INPUT_POST, 'descricao',        FILTER_SANITIZE_SPECIAL_CHARS);
            $marca            = filter_input(INPUT_POST, 'marca',            FILTER_SANITIZE_SPECIAL_CHARS);
            $preco            = filter_input(INPUT_POST, 'preco',            FILTER_VALIDATE_FLOAT);
            $qtdMinima        = filter_input(INPUT_POST, 'quantidade_minima', FILTER_VALIDATE_INT);
            $qtdEstoqueInicial= filter_input(INPUT_POST, 'quantidade_estoque', FILTER_VALIDATE_INT);
            $categoriaId      = filter_input(INPUT_POST, 'categoria_id',     FILTER_VALIDATE_INT);
            $fornecedorId     = filter_input(INPUT_POST, 'fornecedor_id',    FILTER_VALIDATE_INT);

            // Validações
            if (empty($sku) || empty($nome) || $preco === false || $qtdMinima === false) {
                throw new Exception('Preencha todos os campos obrigatórios corretamente.');
            }

            $produto = $id ? ProdutoDAO::buscarPorId($id) : new Produto();
            if (empty($produto)) throw new Exception('Produto não encontrado.');

            $produto->setSku($sku);
            $produto->setNome($nome);
            $produto->setDescricao($descricao ?? '');
            $produto->setMarca($marca ?? '');
            $produto->setPreco($preco);
            $produto->setQuantidadeMinima((int)$qtdMinima);
            $produto->setDataCadastro(new DateTime());

            // Categoria
            if ($categoriaId) {
                $categoria = CategoriaDAO::buscarPorId($categoriaId);
                $produto->setCategoria($categoria);
            }

            // Fornecedor (opcional)
            if ($fornecedorId) {
                $fornecedor = FornecedorDAO::buscarPorId($fornecedorId);
                $produto->setFornecedor($fornecedor);
            } else {
                $produto->setFornecedor(null);
            }

            if (!$id) {
                // Novo produto: define estoque inicial
                $estoqueInicial = (int)($qtdEstoqueInicial ?? 0);
                $produto->setQuantidadeEstoque($estoqueInicial);
                ProdutoDAO::salvar($produto);

                // Registra movimentação de entrada se houver estoque inicial
                if ($estoqueInicial > 0) {
                    $movimentacao = new MovimentacoesEstoque();
                    $movimentacao->setProduto($produto);
                    $movimentacao->setTipo('ENTRADA');
                    $movimentacao->setQuantidade($estoqueInicial);
                    $movimentacao->setSaldoAnterior(0);
                    $movimentacao->setSaldoAtual($estoqueInicial);
                    $movimentacao->setObservacao('Estoque inicial');
                    $movimentacao->setDataMovimentacao(new DateTime());
                    $usuarioId = $_SESSION['usuario']['id'] ?? null;
                    if ($usuarioId) {
                        $usuario = \dao\UsuarioDAO::buscarPorId($usuarioId);
                        $movimentacao->setUsuario($usuario);
                    }
                    MovimentacaoEstoqueDAO::salvar($movimentacao);
                }
            } else {
                ProdutoDAO::atualizar($produto);
            }

            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Produto salvo com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/produtos');
            exit;
        }
    }

    public function deletar(array $params)
    {
        try {
            if (($_SESSION['usuario']['perfil'] ?? '') !== 'ADM') {
                throw new Exception('Acesso negado.');
            }
            $resultado = ProdutoDAO::deletar($params['id']);
            if (!$resultado) throw new Exception('Produto não encontrado.');
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Produto removido com sucesso!'];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/produtos');
            exit;
        }
    }
}