<?php

namespace controller;

use Exception;
use dao\ProdutoDAO;

class AlertaController
{
    public function index()
    {
        try {
            $empresaId    = $_SESSION['usuario']['empresaId'] ?? null;
            $alertas      = $empresaId ? ProdutoDAO::listarEstoqueBaixoPorEmpresa($empresaId) : [];
            $semEstoque   = array_filter($alertas, fn($p) => $p->getQuantidadeEstoque() === 0);
            $criticos     = array_filter($alertas, fn($p) => $p->getQuantidadeEstoque() > 0 && $p->getQuantidadeEstoque() <= $p->getQuantidadeMinima());
            $totalAlertas = count($alertas);
        } catch (Exception $ex) {
            $alertas = $semEstoque = $criticos = [];
            $totalAlertas = 0;
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
        } finally {
            $paginaAtiva  = 'alertas';
            $tituloPagina = 'Alertas de Estoque';
            require __DIR__ . '/../view/alertas.php';
        }
    }
}
