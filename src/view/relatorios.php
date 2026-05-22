<?php
/**
 * @var model\Produto[]              $produtos
 * @var model\MovimentacoesEstoque[] $movimentacoes
 * @var float                                                    $valorTotal
 * @var int                                                      $totalEntradas
 * @var int                                                      $totalSaidas
 * @var array                                                    $topProdutos
 * @var model\Produto[]              $alertas
 * @var int                                                      $totalAlertas
 */
include_once 'template-cabecalho.php';
?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-white mb-1">Relatorios</h2>
        <small class="text-secondary">Visao geral do sistema</small>
    </div>
    <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-relatorio" onclick="baixarRelatorioPdf()">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
    </button>
</div>

<!-- Resumo Geral -->
<div class="row g-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Produtos</span>
                <div class="stat-icon" style="background:rgba(59,130,246,.15);color:#3b82f6;">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="stat-value"><?= count($produtos) ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Valor em Estoque</span>
                <div class="stat-icon" style="background:rgba(16,185,129,.15);color:#10b981;">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="stat-value" style="font-size:1.4rem;">
                R$ <?= number_format($valorTotal, 2, ',', '.') ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Total Entradas</span>
                <div class="stat-icon" style="background:rgba(16,185,129,.15);color:#10b981;">
                    <i class="bi bi-arrow-down-circle"></i>
                </div>
            </div>
            <div class="stat-value text-success"><?= $totalEntradas ?> un</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Total Saidas</span>
                <div class="stat-icon" style="background:rgba(239,68,68,.15);color:#ef4444;">
                    <i class="bi bi-arrow-up-circle"></i>
                </div>
            </div>
            <div class="stat-value text-danger"><?= $totalSaidas ?> un</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Top 5 Produtos Mais Movimentados -->
    <div class="col-12 col-lg-6">
        <div class="table-stockmaster h-100">
            <div class="px-4 py-3 border-bottom border-secondary">
                <h5 class="mb-0 fw-semibold text-white">
                    <i class="bi bi-trophy text-warning me-2"></i>Top 5 Produtos Movimentados
                </h5>
            </div>
            <div class="p-4">
                <?php if (empty($topProdutos)): ?>
                    <div class="text-center text-secondary py-4">
                        <i class="bi bi-bar-chart fs-2 d-block mb-2"></i>
                        Sem movimentacoes registradas.
                    </div>
                <?php else: ?>
                    <?php foreach ($topProdutos as $i => $item): ?>
                        <?php
                        $p   = $item['produto'];
                        $qtd = $item['quantidade'];
                        $max = $topProdutos[0]['quantidade'];
                        $pct = $max > 0 ? ($qtd / $max) * 100 : 0;
                        $cores = ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                        $cor = $cores[$i] ?? '#6366f1';
                        ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-white fw-medium" style="font-size:.875rem;">
                                    <?= $i + 1 ?>. <?= htmlspecialchars($p->getNome()) ?>
                                </span>
                                <span class="text-secondary font-monospace" style="font-size:.875rem;">
                                    <?= $qtd ?> un
                                </span>
                            </div>
                            <div class="stock-bar-wrap">
                                <div class="stock-bar" style="width:<?= $pct ?>%; background:<?= $cor ?>;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Produtos em Alerta -->
    <div class="col-12 col-lg-6">
        <div class="table-stockmaster h-100">
            <div class="px-4 py-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-white">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Alertas de Estoque
                </h5>
                <a href="<?= BASE_URL ?>/alertas" class="btn btn-link btn-sm text-primary p-0">Ver todos</a>
            </div>
            <table class="table table-dark table-hover mb-0">
                <thead>
                <tr>
                    <th>Produto</th>
                    <th class="text-center">Atual</th>
                    <th class="text-center">Minimo</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($alertas)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            Estoque saudavel!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($alertas as $p): ?>
                        <tr>
                            <td class="fw-medium text-white" style="font-size:.875rem;">
                                <?= htmlspecialchars($p->getNome()) ?>
                            </td>
                            <td class="text-center fw-bold <?= $p->getQuantidadeEstoque() === 0 ? 'text-danger' : 'text-warning' ?>">
                                <?= $p->getQuantidadeEstoque() ?>
                            </td>
                            <td class="text-center text-secondary"><?= $p->getQuantidadeMinima() ?></td>
                            <td>
                                <?php if ($p->getQuantidadeEstoque() === 0): ?>
                                    <span class="badge badge-sem-estoque">Sem Estoque</span>
                                <?php else: ?>
                                    <span class="badge badge-critico">Critico</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabela completa de estoque -->
<div class="table-stockmaster mt-4">
    <div class="px-4 py-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-white">
            <i class="bi bi-table me-2 text-primary"></i>Posicao Atual do Estoque
        </h5>
        <small class="text-secondary"><?= count($produtos) ?> produtos</small>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover mb-0">
            <thead>
            <tr>
                <th>Produto</th>
                <th>SKU</th>
                <th>Categoria</th>
                <th class="text-center">Estoque</th>
                <th class="text-end">Preco Unit.</th>
                <th class="text-end">Valor Total</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($produtos as $p): ?>
                <?php
                $qtd = $p->getQuantidadeEstoque();
                $min = $p->getQuantidadeMinima();
                $valorProd = $p->getPreco() * $qtd;
                if ($qtd === 0){
                    $badgeCls = 'badge-sem-estoque';
                    $badgeTxt = 'Sem Estoque';}
                elseif ($qtd <= $min) {
                    $badgeCls = 'badge-critico';
                    $badgeTxt = 'Critico'; }
                elseif ($qtd <= $min*1.5) {
                    $badgeCls = 'badge-atencao';
                    $badgeTxt = 'Atencao'; }
                else {
                    $badgeCls = 'badge-normal';
                    $badgeTxt = 'Normal'; }
                ?>
                <tr>
                    <td class="fw-medium text-white"><?= htmlspecialchars($p->getNome()) ?></td>
                    <td><code class="text-secondary"><?= htmlspecialchars($p->getSku()) ?></code></td>
                    <td class="text-secondary"><?= htmlspecialchars($p->getCategoria()?->getNome() ?? '—') ?></td>
                    <td class="text-center font-monospace <?= $qtd === 0 ? 'text-danger' : 'text-white' ?>">
                        <?= $qtd ?>
                    </td>
                    <td class="text-end text-secondary">R$ <?= number_format($p->getPreco(), 2, ',', '.') ?></td>
                    <td class="text-end fw-bold text-white">R$ <?= number_format($valorProd, 2, ',', '.') ?></td>
                    <td><span class="badge <?= $badgeCls ?>"><?= $badgeTxt ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot class="border-top border-secondary">
            <tr>
                <td colspan="5" class="text-end fw-bold text-secondary py-3">TOTAL EM ESTOQUE:</td>
                <td class="text-end fw-bold text-success py-3">
                    R$ <?= number_format($valorTotal, 2, ',', '.') ?>
                </td>
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    window.relatorioDados = {
        resumo: {
            periodo: 'Ultimos 30 dias',
            totalEntradas: <?= (int)$totalEntradas30 ?>,
            totalSaidas: <?= (int)$totalSaidas30 ?>,
            skus: <?= count($produtos) ?>,
            valorTotalEstoque: <?= (float)$valorTotal ?>,
            valorEntradas: <?= (float)$valorEntradas30 ?>,
            valorSaidas: <?= (float)$valorSaidas30 ?>
        },
        topProdutos: <?= json_encode(array_map(function ($item) {
            $p = $item['produto'];
            return [
                'nome' => $p?->getNome() ?? '—',
                'quantidade' => (int)($item['quantidade'] ?? 0),
                'valor' => (float)($item['valor'] ?? 0)
            ];
        }, $topProdutos30 ?? []), JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        valorPorCategoria: <?= json_encode(array_map(function ($item) {
            return [
                'categoria' => $item['nome'] ?? 'Sem categoria',
                'valor' => (float)($item['valor'] ?? 0)
            ];
        }, $valorPorCategoria ?? []), JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        topPorCategoria: <?= json_encode(array_map(function ($item) {
            return [
                'categoria' => $item['categoria'] ?? 'Sem categoria',
                'produto' => $item['produto']?->getNome() ?? '—',
                'quantidade' => (int)($item['quantidade'] ?? 0),
                'valor' => (float)($item['valor'] ?? 0)
            ];
        }, $topPorCategoria ?? []), JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>,
        estoque: <?= json_encode(array_map(function ($p) {
            $qtd = $p->getQuantidadeEstoque();
            $valorTotal = (float)$p->getPreco() * $qtd;
            return [
                'nome' => $p->getNome(),
                'sku' => $p->getSku(),
                'categoria' => $p->getCategoria()?->getNome() ?? '—',
                'fornecedor' => $p->getFornecedor()?->getNome() ?? '—',
                'quantidade' => $qtd,
                'preco' => (float)$p->getPreco(),
                'valorTotal' => $valorTotal
            ];
        }, $produtos), JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>
    };
</script>

<?php include_once 'template-rodape.php'; ?>
