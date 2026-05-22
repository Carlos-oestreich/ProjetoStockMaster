<?php
/** @var model\MovimentacoesEstoque[] $movimentacoes */
include_once 'template-cabecalho.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Movimentacoes</h2>
            <small class="text-secondary"><?= count($movimentacoes) ?> registro(s)</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-movimentacoes">
                <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
            </button>
            <a href="<?= BASE_URL ?>/movimentacoes/nova" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Nova Movimentacao
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filtro-busca" class="form-control" placeholder="Buscar produto...">
            </div>
        </div>
        <div class="col-md-3">
            <select id="filtro-tipo" class="form-select">
                <option value="">Todos os Tipos</option>
                <option value="ENTRADA">Entrada</option>
                <option value="SAIDA">Saida</option>
            </select>
        </div>
    </div>

    <div class="table-stockmaster">
        <table class="table table-dark table-hover mb-0" id="tabela-mov">
            <thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Produto</th>
                <th>Operador</th>
                <th class="text-center">Qtd</th>
                <th class="text-center">Saldo Anterior</th>
                <th class="text-center">Saldo Atual</th>
                <th>Observacao</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($movimentacoes)): ?>
                <tr>
                    <td colspan="8" class="text-center text-secondary py-5">
                        <i class="bi bi-arrow-left-right fs-2 d-block mb-2"></i>
                        Nenhuma movimentacao registrada.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach (array_reverse($movimentacoes) as $mov): ?>
                    <?php
                    $tipo    = $mov->getTipo();
                    $produto = $mov->getProduto();
                    $usuario = $mov->getUsuario();
                    ?>
                    <tr data-produto="<?= strtolower(htmlspecialchars($produto?->getNome() ?? '')) ?>"
                        data-tipo="<?= $tipo ?>">
                        <td class="text-secondary" style="white-space:nowrap;">
                            <?= $mov->getDataMovimentacao()->format('d/m/Y H:i') ?>
                        </td>
                        <td>
                            <?php if ($tipo === 'ENTRADA'): ?>
                                <span class="badge d-flex align-items-center gap-1" style="background:rgba(16,185,129,.2);color:#10b981;width:fit-content;">
                                    <i class="bi bi-arrow-down-circle"></i> ENTRADA
                                </span>
                            <?php else: ?>
                                <span class="badge d-flex align-items-center gap-1" style="background:rgba(239,68,68,.2);color:#ef4444;width:fit-content;">
                                    <i class="bi bi-arrow-up-circle"></i> SAIDA
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-medium text-white">
                            <?= htmlspecialchars($produto?->getNome() ?? '—') ?>
                        </td>
                        <td class="text-secondary">
                            <?= htmlspecialchars($usuario?->getNome() ?? '—') ?>
                        </td>
                        <td class="text-center fw-bold font-monospace <?= $tipo === 'ENTRADA' ? 'text-success' : 'text-danger' ?>">
                            <?= $tipo === 'ENTRADA' ? '+' : '-' ?><?= $mov->getQuantidade() ?>
                        </td>
                        <td class="text-center text-secondary font-monospace"><?= $mov->getSaldoAnterior() ?></td>
                        <td class="text-center font-monospace text-white"><?= $mov->getSaldoAtual() ?></td>
                        <td class="text-secondary" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            <?= htmlspecialchars($mov->getObservacao() ?? '—') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        (function () {
            const busca   = document.getElementById('filtro-busca');
            const tipoSel = document.getElementById('filtro-tipo');
            const linhas  = document.querySelectorAll('#tabela-mov tbody tr[data-produto]');

            function filtrar() {
                const termo = busca.value.toLowerCase();
                const tipo  = tipoSel.value;
                linhas.forEach(tr => {
                    const okProd = !termo || tr.dataset.produto.includes(termo);
                    const okTipo = !tipo  || tr.dataset.tipo === tipo;
                    tr.style.display = (okProd && okTipo) ? '' : 'none';
                });
            }

            busca.addEventListener('input', filtrar);
            tipoSel.addEventListener('change', filtrar);
        })();

        (function () {
            const btn = document.getElementById('btn-pdf-movimentacoes');
            if (!btn) return;

            const colunas = <?= json_encode(['Data', 'Tipo', 'Produto', 'Operador', 'Qtd', 'Saldo Anterior', 'Saldo Atual', 'Observacao']) ?>;
            const linhas = <?= json_encode(array_map(function ($mov) {
                return [
                    $mov->getDataMovimentacao()->format('d/m/Y H:i'),
                    $mov->getTipo(),
                    $mov->getProduto()?->getNome() ?? '—',
                    $mov->getUsuario()?->getNome() ?? '—',
                    $mov->getQuantidade(),
                    $mov->getSaldoAnterior(),
                    $mov->getSaldoAtual(),
                    $mov->getObservacao() ?? '—',
                ];
            }, array_reverse($movimentacoes)), JSON_UNESCAPED_UNICODE) ?>;

            btn.addEventListener('click', function () {
                gerarPDF({
                    titulo: 'Movimentacoes',
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