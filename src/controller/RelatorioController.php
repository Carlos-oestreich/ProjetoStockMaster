<?php

namespace controller;

use Exception;
use dao\ProdutoDAO;
use dao\MovimentacaoEstoqueDAO;
use dao\CategoriaDAO;

class RelatorioController
{
    public function index()
    {
        try {
            $produtos      = ProdutoDAO::listar();
            $movimentacoes = MovimentacaoEstoqueDAO::listar();
            $categorias    = CategoriaDAO::listar();
            $alertas       = ProdutoDAO::listarEstoqueBaixo();
            $totalAlertas  = count($alertas);

            // Valor total em estoque
            $valorTotal = array_reduce($produtos, fn($carry, $p) => $carry + ($p->getPreco() * $p->getQuantidadeEstoque()), 0);

            // Totais de movimentacao
            $totalEntradas = 0;
            $totalSaidas   = 0;
            foreach ($movimentacoes as $m) {
                if ($m->getTipo() === 'ENTRADA') $totalEntradas += $m->getQuantidade();
                else                             $totalSaidas   += $m->getQuantidade();
            }

            // Top 5 produtos mais movimentados
            $contagem = [];
            foreach ($movimentacoes as $m) {
                $pid = $m->getProduto()?->getId();
                if ($pid) $contagem[$pid] = ($contagem[$pid] ?? 0) + $m->getQuantidade();
            }
            arsort($contagem);
            $topProdutos = [];
            foreach (array_slice($contagem, 0, 5, true) as $pid => $qtd) {
                $prod = ProdutoDAO::buscarPorId($pid);
                if ($prod) $topProdutos[] = ['produto' => $prod, 'quantidade' => $qtd];
            }

        } catch (Exception $ex) {
            $produtos = $movimentacoes = $categorias = $alertas = $topProdutos = [];
            $valorTotal = $totalEntradas = $totalSaidas = $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'relatorios';
            $tituloPagina = 'Relatorios';
            require __DIR__ . '/../view/relatorios.php';
        }
    }
}