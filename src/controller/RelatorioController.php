<?php

namespace controller;

use Exception;
use dao\ProdutoDAO;
use dao\MovimentacaoEstoqueDAO;
use dao\CategoriaDAO;
use dao\EmpresaDAO;

class RelatorioController
{
    public function index()
    {
        try {
            $empresaId     = $_SESSION['usuario']['empresaId'] ?? null;
            $empresa       = $empresaId ? EmpresaDAO::buscarPorId($empresaId) : null;
            $produtos      = $empresaId ? ProdutoDAO::listarPorEmpresa($empresaId) : [];
            $movimentacoes = $empresaId ? MovimentacaoEstoqueDAO::listarPorEmpresa($empresaId) : [];
            $categorias    = $empresaId ? CategoriaDAO::listarPorEmpresa($empresaId) : [];
            $alertas       = $empresaId ? ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId) : [];
            $totalAlertas  = count($alertas);

            // Valor total em estoque
            $valorTotal = array_reduce($produtos, fn($carry, $p) => $carry + ($p->getPreco() * $p->getQuantidadeEstoque()), 0);

            // Totais de movimentacao (geral)
            $totalEntradas = 0;
            $totalSaidas   = 0;
            foreach ($movimentacoes as $m) {
                if ($m->getTipo() === 'ENTRADA') $totalEntradas += $m->getQuantidade();
                else                             $totalSaidas   += $m->getQuantidade();
            }

            // Dados dos ultimos 30 dias
            $inicio30 = (new \DateTime())->modify('-30 days');
            $movimentacoes30 = array_filter($movimentacoes, function ($m) use ($inicio30) {
                return $m->getDataMovimentacao() >= $inicio30;
            });

            $totalEntradas30 = 0;
            $totalSaidas30   = 0;
            $valorEntradas30 = 0.0;
            $valorSaidas30   = 0.0;

            $saidaPorProduto = [];
            $valorPorProduto = [];
            $valorPorCategoria = [];
            $itensCategoria = [];

            foreach ($movimentacoes30 as $m) {
                $produto = $m->getProduto();
                if (!$produto) {
                    continue;
                }

                $qtd = $m->getQuantidade();
                $preco = (float)$produto->getPreco();
                if ($m->getTipo() === 'ENTRADA') {
                    $totalEntradas30 += $qtd;
                    $valorEntradas30 += $qtd * $preco;
                } else {
                    $totalSaidas30 += $qtd;
                    $valorSaidas30 += $qtd * $preco;

                    $pid = $produto->getId();
                    $saidaPorProduto[$pid] = ($saidaPorProduto[$pid] ?? 0) + $qtd;
                    $valorPorProduto[$pid] = ($valorPorProduto[$pid] ?? 0) + ($qtd * $preco);

                    $categoria = $produto->getCategoria();
                    $cid = $categoria?->getId() ?? 0;
                    $nomeCat = $categoria?->getNome() ?? 'Sem categoria';
                    if (!isset($valorPorCategoria[$cid])) {
                        $valorPorCategoria[$cid] = ['nome' => $nomeCat, 'valor' => 0.0];
                    }
                    $valorPorCategoria[$cid]['valor'] += $qtd * $preco;

                    if (!isset($itensCategoria[$cid])) {
                        $itensCategoria[$cid] = [];
                    }
                    $itensCategoria[$cid][$pid] = ($itensCategoria[$cid][$pid] ?? 0) + $qtd;
                }
            }

            // Top 5 produtos mais vendidos (30 dias)
            arsort($saidaPorProduto);
            $topProdutos30 = [];
            foreach (array_slice($saidaPorProduto, 0, 5, true) as $pid => $qtd) {
                $prod = ProdutoDAO::buscarPorId($pid);
                if ($prod) {
                    $topProdutos30[] = [
                        'produto' => $prod,
                        'quantidade' => $qtd,
                        'valor' => $valorPorProduto[$pid] ?? 0,
                    ];
                }
            }

            // Itens mais vendidos por categoria (30 dias)
            $topPorCategoria = [];
            foreach ($itensCategoria as $cid => $produtosCat) {
                arsort($produtosCat);
                $pidTop = array_key_first($produtosCat);
                $qtdTop = $produtosCat[$pidTop] ?? 0;
                $prodTop = $pidTop ? ProdutoDAO::buscarPorId($pidTop) : null;
                $topPorCategoria[] = [
                    'categoriaId' => $cid,
                    'categoria' => $valorPorCategoria[$cid]['nome'] ?? 'Sem categoria',
                    'produto' => $prodTop,
                    'quantidade' => $qtdTop,
                    'valor' => $prodTop ? $qtdTop * (float)$prodTop->getPreco() : 0,
                ];
            }

            // Top 5 produtos mais movimentados (geral)
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
            $empresa = null;
            $valorTotal = $totalEntradas = $totalSaidas = $totalAlertas = 0;
            $movimentacoes30 = [];
            $totalEntradas30 = $totalSaidas30 = 0;
            $valorEntradas30 = $valorSaidas30 = 0.0;
            $topProdutos30 = [];
            $valorPorCategoria = [];
            $topPorCategoria = [];
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'relatorios';
            $tituloPagina = 'Relatorios';
            require __DIR__ . '/../view/relatorios.php';
        }
    }
}