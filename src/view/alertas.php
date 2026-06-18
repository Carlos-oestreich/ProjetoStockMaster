<?php
/**
 * @var model\Produto[] $alertas
 * @var model\Produto[] $semEstoque
 * @var model\Produto[] $criticos
 * @var int                                         $totalAlertas
 */
include_once 'template-cabecalho.php';
?>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-white mb-1">Alertas de Estoque</h2>
        <small class="text-secondary"><?= $totalAlertas ?> produto(s) precisam de atencao</small>
    </div>
    <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-alertas">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
    </button>
</div>

<!-- Cards de resumo dos alertas -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Total em Alerta</span>
                <div class="stat-icon" style="background:rgba(239,68,68,.15); color:#ef4444;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
            <div class="stat-value text-danger"><?= $totalAlertas ?></div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Sem Estoque</span>
                <div class="stat-icon" style="background:rgba(100,116,139,.15); color:#94a3b8;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
            <div class="stat-value" style="color:#94a3b8;"><?= count($semEstoque) ?></div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-stat">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="stat-label">Criticos</span>
                <div class="stat-icon" style="background:rgba(245,158,11,.15); color:#f59e0b;">
                    <i class="bi bi-dash-circle-fill"></i>
                </div>
            </div>
            <div class="stat-value text-warning"><?= count($criticos) ?></div>
        </div>
    </div>
</div>

<?php if (empty($alertas)): ?>
    <!-- Estado vazio -->
    <div class="text-center py-5" style="background:#1e293b; border-radius:16px; border:1px solid #334155;">
        <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
        <h4 class="text-white mt-3">Estoque saudavel!</h4>
        <p class="text-secondary">Todos os produtos estao dentro dos limites minimos configurados.</p>
        <a href="<?= BASE_URL ?>/produtos" class="btn btn-outline-primary mt-2">
            <i class="bi bi-box-seam me-2"></i>Ver Produtos
        </a>
    </div>

<?php else: ?>

    <div class="table-stockmaster">
        <div class="px-4 py-3 border-bottom border-secondary d-flex align-items-center gap-2">
            <i class="bi bi-bell-fill text-danger"></i>
            <h5 class="mb-0 fw-semibold text-white">Produtos que precisam de reposicao</h5>
        </div>
        <table class="table table-dark table-hover mb-0">
            <thead>
            <tr>
                <th>Produto</th>
                <th>SKU</th>
                <th>Categoria</th>
                <th>Fornecedor</th>
                <th class="text-center">Estoque Atual</th>
                <th class="text-center">Minimo</th>
                <th>Situacao</th>
                <th class="text-end">Acao</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($alertas as $p): ?>
                <?php
                $qtd = $p->getQuantidadeEstoque();
                $min = $p->getQuantidadeMinima();
                $semEst = $qtd === 0;
                ?>
                <tr>
                    <td class="fw-medium text-white"><?= htmlspecialchars($p->getNome()) ?></td>
                    <td><code class="text-secondary"><?= htmlspecialchars($p->getSku()) ?></code></td>
                    <td class="text-secondary"><?= htmlspecialchars($p->getCategoria()?->getNome() ?? '—') ?></td>
                    <td class="text-secondary"><?= htmlspecialchars($p->getFornecedor()?->getNome() ?? '—') ?></td>
                    <td class="text-center fw-bold <?= $semEst ? 'text-danger' : 'text-warning' ?>">
                        <?= $qtd ?> un
                    </td>
                    <td class="text-center text-secondary"><?= $min ?> un</td>
                    <td>
                        <?php if ($semEst): ?>
                            <span class="badge badge-sem-estoque">Sem Estoque</span>
                        <?php else: ?>
                            <span class="badge badge-critico">Critico</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= BASE_URL ?>/movimentacoes/nova?produto=<?= $p->getId() ?>"
                           class="btn btn-sm btn-success" title="Registrar entrada">
                            <i class="bi bi-plus-circle me-1"></i>Entrada
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>

<script>
    (function () {
        const btn = document.getElementById('btn-pdf-alertas');
        if (!btn) return;

        const colunas = <?= json_encode(['Produto', 'SKU', 'Categoria', 'Fornecedor', 'Estoque Atual', 'Minimo', 'Situacao']) ?>;
        const linhas = <?= json_encode(array_map(function ($p) {
            $qtd = $p->getQuantidadeEstoque();
            $min = $p->getQuantidadeMinima();
            $status = $qtd === 0 ? 'Sem Estoque' : 'Critico';
            return [
                $p->getNome(),
                $p->getSku(),
                $p->getCategoria()?->getNome() ?? '—',
                $p->getFornecedor()?->getNome() ?? '—',
                $qtd,
                $min,
                $status,
            ];
        }, $alertas), JSON_UNESCAPED_UNICODE) ?>;

        btn.addEventListener('click', function () {
            gerarPDF({
                titulo: 'Alertas de Estoque',
                orientacao: 'portrait',
                colunas: colunas,
                linhas: linhas,
                empresa: window.stockmasterEmpresa || {},
                logo: window.stockmasterEmpresa?.logo || null,
            });
        });
    })();
</script>

<?php include_once 'template-rodape.php'; ?>
