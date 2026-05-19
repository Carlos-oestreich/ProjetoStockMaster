<?php

namespace controller;

use Exception;
use dao\ProdutoDAO;
use dao\MovimentacaoEstoqueDAO;
use dao\CategoriaDAO;

class DashboardController
{
    public function index()
    {
        try {
            $empresaId      = $_SESSION['usuario']['empresaId'] ?? null;
            $produtos       = $empresaId ? ProdutoDAO::listarPorEmpresa($empresaId) : [];
            $movimentacoes  = $empresaId ? MovimentacaoEstoqueDAO::listarPorEmpresa($empresaId) : [];
            $alertas        = $empresaId ? ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId) : [];

            // Valor total em estoque
            $valorEstoque = 0;
            foreach ($produtos as $p) {
                $valorEstoque += $p->getPreco() * $p->getQuantidadeEstoque();
            }

            // Movimentações de hoje
            $hoje = (new \DateTime())->format('Y-m-d');
            $movimentacoesHoje = array_filter($movimentacoes, function ($m) use ($hoje) {
                return $m->getDataMovimentacao()->format('Y-m-d') === $hoje;
            });

            // Últimas 5 movimentações
            $ultimasMovimentacoes = array_slice(
                array_reverse($movimentacoes),
                0, 5
            );

            // Total de alertas (para o sino)
            $totalAlertas = count($alertas);

        } catch (Exception $ex) {
            $produtos = $movimentacoes = $alertas = $ultimasMovimentacoes = [];
            $valorEstoque = 0;
            $movimentacoesHoje = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'dashboard';
            $tituloPagina = 'Dashboard';
            require __DIR__ . '/../view/dashboard.php';
        }
    }
}
