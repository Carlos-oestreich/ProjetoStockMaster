<?php

namespace controller;

use DateTime;
use Exception;
use dao\MovimentacaoEstoqueDAO;
use dao\ProdutoDAO;
use dao\UsuarioDAO;
use dao\EmpresaDAO;
use model\MovimentacoesEstoque;

class MovimentacaoController
{
    public function nova()
    {
        return $this->cadastrar();
    }

    public function listar()
    {
        try {
            $empresaId     = $_SESSION['usuario']['empresaId'] ?? null;
            $movimentacoes = $empresaId ? MovimentacaoEstoqueDAO::listarPorEmpresa($empresaId) : [];
            $totalAlertas  = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        } catch (Exception $ex) {
            $movimentacoes = [];
            $totalAlertas  = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'movimentacoes';
            $tituloPagina = 'Movimentacoes';
            require __DIR__ . '/../view/lista-movimentacoes.php';
        }
    }

    public function cadastrar()
    {
        try {
            $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
            $produtos     = $empresaId ? ProdutoDAO::listarPorEmpresa($empresaId) : [];
            $totalAlertas = $empresaId ? count(ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId)) : 0;
        } catch (Exception $ex) {
            $produtos = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'movimentacoes';
            $tituloPagina = 'Nova Movimentacao';
            require __DIR__ . '/../view/cadastro-movimentacao.php';
        }
    }

    public function salvar(array $params)
    {
        try {
            $tipo       = filter_input(INPUT_POST, 'tipo',       FILTER_SANITIZE_SPECIAL_CHARS);
            $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
            $produtoId  = filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT);
            $observacao = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_SPECIAL_CHARS);
            $empresaId  = $_SESSION['usuario']['empresaId'] ?? null;

            if (!in_array($tipo, ['ENTRADA', 'SAIDA'], true)) {
                throw new Exception('Tipo de movimentacao invalido.');
            }
            if ($quantidade === false || $quantidade <= 0) {
                throw new Exception('Quantidade deve ser maior que zero.');
            }
            if (empty($produtoId)) {
                throw new Exception('Selecione um produto.');
            }

            $produto = $empresaId ? ProdutoDAO::buscarPorIdEEmpresa($produtoId, $empresaId) : null;
            if (empty($produto)) throw new Exception('Produto nao encontrado.');

            $saldoAnterior = $produto->getQuantidadeEstoque();

            if ($tipo === 'SAIDA' && $saldoAnterior < $quantidade) {
                throw new Exception(
                    "Saldo insuficiente. Estoque atual: {$saldoAnterior} unidades."
                );
            }

            // Atualiza estoque do produto
            $novoSaldo = $tipo === 'ENTRADA'
                ? $saldoAnterior + $quantidade
                : $saldoAnterior - $quantidade;

            $produto->setQuantidadeEstoque($novoSaldo);
            ProdutoDAO::atualizar($produto);

            // Registra movimentacao
            $mov = new MovimentacoesEstoque();
            $mov->setProduto($produto);
            $mov->setTipo($tipo);
            $mov->setQuantidade($quantidade);
            $mov->setSaldoAnterior($saldoAnterior);
            $mov->setSaldoAtual($novoSaldo);
            $mov->setObservacao($observacao ?? '');
            $mov->setDataMovimentacao(new DateTime());

            $usuarioId = $_SESSION['usuario']['id'] ?? null;
            if ($usuarioId) {
                $usuario = UsuarioDAO::buscarPorId($usuarioId);
                $mov->setUsuario($usuario);
            }

            if ($empresaId) {
                $empresa = EmpresaDAO::buscarPorId($empresaId);
                $mov->setEmpresa($empresa);
            }

            MovimentacaoEstoqueDAO::salvar($mov);

            $_SESSION['flash'] = [
                'tipo'     => 'success',
                'mensagem' => "Movimentacao de {$tipo} registrada! Novo saldo: {$novoSaldo} unidades."
            ];
        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            header('Location: ' . BASE_URL . '/movimentacoes');
            exit;
        }
    }
}