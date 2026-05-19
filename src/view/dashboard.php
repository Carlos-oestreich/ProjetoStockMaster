<?php
/**
 * @var model\Produto[]              $produtos
 * @var model\MovimentacoesEstoque[] $movimentacoes
 * @var model\Produto[]              $alertas
 * @var model\MovimentacoesEstoque[] $ultimasMovimentacoes
 * @var float                                                    $valorEstoque
 * @var array                                                    $movimentacoesHoje
 * @var int                                                      $totalAlertas
 */
$paginaAtiva  = 'dashboard';
$tituloPagina = 'Dashboard';
include_once 'template-cabecalho.php';
?>

    <!-- =========================================================
         CARDS DE RESUMO
         ========================================================= -->
    <div class="row g-4 mb-4">

        <!-- Total de Produtos -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="stat-label">Total de Produtos</span>
                    <div class="stat-icon" style="background:rgba(59,130,246,.15); color:#3b82f6;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="stat-value"><?= count($produtos) ?></div>
            </div>
        </div>

        <!-- Valor em Estoque -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="stat-label">Valor em Estoque</span>
                    <div class="stat-icon" style="background:rgba(16,185,129,.15); color:#10b981;">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                <div class="stat-value">
                    R$ <?= number_format($valorEstoque, 2, ',', '.') ?>
                </div>
            </div>
        </div>

        <!-- Produtos em Alerta -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="stat-label">Produtos em Alerta</span>
                    <div class="stat-icon" style="background:rgba(239,68,68,.15); color:#ef4444;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
                <div class="stat-value text-danger"><?= count($alertas) ?></div>
            </div>
        </div>

        <!-- Movimentações Hoje -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-stat">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="stat-label">Movimentações Hoje</span>
                    <div class="stat-icon" style="background:rgba(99,102,241,.15); color:#6366f1;">
                        <i class="bi bi-activity"></i>
                    </div>
                </div>
                <div class="stat-value"><?= count($movimentacoesHoje) ?></div>
            </div>
        </div>

    </div><!-- /cards -->

    <!-- =========================================================
         LINHA: MOVIMENTAÇÕES RECENTES + ALERTAS
         ========================================================= -->
    <div class="row g-4">

        <!-- Movimentações Recentes -->
        <div class="col-12 col-xl-8">
            <div class="table-stockmaster">
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-secondary">
                    <h5 class="mb-0 fw-semibold text-white">Movimentações Recentes</h5>
                    <a href="<?= BASE_URL ?>/movimentacoes" class="btn btn-link btn-sm text-primary p-0">Ver todas</a>
                </div>
                <table class="table table-dark table-hover mb-0">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Tipo</th>
                        <th>Produto</th>
                        <th class="text-end">Qtd</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($ultimasMovimentacoes)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Nenhuma movimentação registrada.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ultimasMovimentacoes as $mov): ?>
                            <tr>
                                <td class="text-secondary">
                                    <?= $mov->getDataMovimentacao()->format('d/m/Y H:i') ?>
                                </td>
                                <td>
                                    <?php if ($mov->getTipo() === 'ENTRADA'): ?>
                                        <span class="badge" style="background:rgba(16,185,129,.2);color:#10b981;">
                                            <i class="bi bi-arrow-down-circle me-1"></i>ENTRADA
                                        </span>
                                    <?php else: ?>
                                        <span class="badge" style="background:rgba(239,68,68,.2);color:#ef4444;">
                                            <i class="bi bi-arrow-up-circle me-1"></i>SAÍDA
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-medium text-white">
                                    <?= htmlspecialchars($mov->getProduto()?->getNome() ?? '—') ?>
                                </td>
                                <td class="text-end fw-bold font-monospace">
                                    <?= $mov->getQuantidade() ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alertas de Estoque Baixo -->
        <div class="col-12 col-xl-4">
            <div class="table-stockmaster h-100">
                <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom border-secondary">
                    <h5 class="mb-0 fw-semibold text-white d-flex align-items-center gap-2">
                        <i class="bi bi-bell-fill text-danger"></i> Alertas Ativos
                    </h5>
                    <a href="<?= BASE_URL ?>/alertas" class="btn btn-link btn-sm text-primary p-0">Ver todos</a>
                </div>
                <div class="p-3 d-flex flex-column gap-2" style="max-height:360px; overflow-y:auto;">
                    <?php if (empty($alertas)): ?>
                        <div class="text-center text-secondary py-5">
                            <i class="bi bi-check-circle-fill text-success fs-2 d-block mb-2"></i>
                            <small>Estoque saudável!<br>Nenhum alerta no momento.</small>
                        </div>
                    <?php else: ?>
                        <?php foreach (array_slice($alertas, 0, 6) as $p): ?>
                            <div class="rounded-3 p-3 d-flex justify-content-between align-items-center"
                                 style="background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2);">
                                <div>
                                    <div class="fw-medium text-white" style="font-size:.875rem;">
                                        <?= htmlspecialchars($p->getNome()) ?>
                                    </div>
                                    <small class="text-secondary">SKU: <?= htmlspecialchars($p->getSku()) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold <?= $p->getQuantidadeEstoque() == 0 ? 'text-danger' : 'text-warning' ?>">
                                        <?= $p->getQuantidadeEstoque() ?> un
                                    </div>
                                    <small class="text-secondary">Mín: <?= $p->getQuantidadeMinima() ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div><!-- /row -->

<?php include_once 'template-rodape.php'; ?>