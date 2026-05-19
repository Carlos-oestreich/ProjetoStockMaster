<?php
/**
 * @var model\Produto[]    $produtos
 * @var model\Categoria[]  $categorias
 * @var model\Fornecedor[] $fornecedores
 * @var int                                            $totalAlertas
 */
include_once 'template-cabecalho.php';

$isAdmin = in_array(($_SESSION['usuario']['perfil'] ?? ''), ['ADM', 'DONO'], true);

// Função helper para badge de status
function badgeStatus(int $qtd, int $minima): string {
    if ($qtd === 0)        return '<span class="badge badge-sem-estoque">Sem Estoque</span>';
    if ($qtd <= $minima)   return '<span class="badge badge-critico">Crítico</span>';
    if ($qtd <= $minima * 1.5) return '<span class="badge badge-atencao">Atenção</span>';
    return '<span class="badge badge-normal">Normal</span>';
}
function corBarra(int $qtd, int $min): string {
    if ($qtd === 0)       return '#6b7280';
    if ($qtd <= $min)     return '#ef4444';
    if ($qtd <= $min*1.5) return '#f59e0b';
    return '#10b981';
}
?>

    <!-- Cabeçalho da página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Produtos</h2>
            <small class="text-secondary"><?= count($produtos) ?> produto(s) cadastrado(s)</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-produtos">
                <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
            </button>
            <?php if ($isAdmin): ?>
                <a href="<?= BASE_URL ?>/produtos/novo" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i> Novo Produto
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filtro-busca" class="form-control" placeholder="Buscar por nome ou SKU...">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <select id="filtro-categoria" class="form-select">
                <option value="">Todas as Categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat->getNome()) ?>">
                        <?= htmlspecialchars($cat->getNome()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select id="filtro-status" class="form-select">
                <option value="">Todos os Status</option>
                <option value="Normal">Normal</option>
                <option value="Atenção">Atenção</option>
                <option value="Crítico">Crítico</option>
                <option value="Sem Estoque">Sem Estoque</option>
            </select>
        </div>
    </div>

    <!-- Tabela -->
    <div class="table-stockmaster">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0" id="tabela-produtos">
                <thead>
                <tr>
                    <th>SKU</th>
                    <th>Produto</th>
                    <th>Categoria</th>
                    <th>Fornecedor</th>
                    <th>Estoque</th>
                    <?php if ($isAdmin): ?><th>Preço</th><?php endif; ?>
                    <th>Status</th>
                    <?php if ($isAdmin): ?><th class="text-end">Ações</th><?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="<?= $isAdmin ? 8 : 6 ?>" class="text-center text-secondary py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Nenhum produto cadastrado.
                            <?php if ($isAdmin): ?>
                                <a href="<?= BASE_URL ?>/produtos/novo" class="d-block mt-2 text-primary">Cadastrar primeiro produto</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $p): ?>
                        <?php
                        $qtd = $p->getQuantidadeEstoque();
                        $min = $p->getQuantidadeMinima();
                        $pct = $min > 0 ? min(100, ($qtd / $min) * 100) : 100;
                        $cor = corBarra($qtd, $min);
                        $catNome  = $p->getCategoria()  ? htmlspecialchars($p->getCategoria()->getNome())  : '—';
                        $fornNome = $p->getFornecedor() ? htmlspecialchars($p->getFornecedor()->getNome()) : '—';
                        ?>
                        <tr data-nome="<?= strtolower(htmlspecialchars($p->getNome())) ?>"
                            data-sku="<?= strtolower(htmlspecialchars($p->getSku())) ?>"
                            data-categoria="<?= $catNome ?>"
                            data-status="<?= $qtd === 0 ? 'Sem Estoque' : ($qtd <= $min ? 'Crítico' : ($qtd <= $min*1.5 ? 'Atenção' : 'Normal')) ?>">

                            <td><code class="text-secondary"><?= htmlspecialchars($p->getSku()) ?></code></td>
                            <td class="fw-medium text-white"><?= htmlspecialchars($p->getNome()) ?></td>
                            <td class="text-secondary"><?= $catNome ?></td>
                            <td class="text-secondary"><?= $fornNome ?></td>
                            <td style="min-width:130px;">
                                <div class="d-flex justify-content-between mb-1" style="font-size:.8rem;">
                                    <strong class="<?= $qtd === 0 ? 'text-danger' : 'text-white' ?>"><?= $qtd ?> un</strong>
                                    <span class="text-secondary">Mín: <?= $min ?></span>
                                </div>
                                <div class="stock-bar-wrap">
                                    <div class="stock-bar" style="width:<?= $pct ?>%; background:<?= $cor ?>;"></div>
                                </div>
                            </td>
                            <?php if ($isAdmin): ?>
                                <td class="text-white">R$ <?= number_format($p->getPreco(), 2, ',', '.') ?></td>
                            <?php endif; ?>
                            <td><?= badgeStatus($qtd, $min) ?></td>
                            <?php if ($isAdmin): ?>
                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>/produtos/<?= $p->getId() ?>"
                                       class="btn btn-icon btn-outline-primary me-1" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>/produtos/<?= $p->getId() ?>/deletar"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirmarExclusao('Remover o produto «<?= htmlspecialchars($p->getNome(), ENT_QUOTES) ?>»?')">
                                        <button type="submit" class="btn btn-icon btn-outline-danger" title="Excluir">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script de filtro client-side -->
    <script>
        (function () {
            const busca    = document.getElementById('filtro-busca');
            const catSel   = document.getElementById('filtro-categoria');
            const statSel  = document.getElementById('filtro-status');
            const linhas   = document.querySelectorAll('#tabela-produtos tbody tr[data-nome]');

            function filtrar() {
                const termo = busca.value.toLowerCase();
                const cat   = catSel.value.toLowerCase();
                const stat  = statSel.value.toLowerCase();

                linhas.forEach(tr => {
                    const nome = tr.dataset.nome;
                    const sku  = tr.dataset.sku;
                    const trCat  = tr.dataset.categoria.toLowerCase();
                    const trStat = tr.dataset.status.toLowerCase();

                    const okTermo = !termo || nome.includes(termo) || sku.includes(termo);
                    const okCat   = !cat   || trCat === cat;
                    const okStat  = !stat  || trStat === stat;

                    tr.style.display = (okTermo && okCat && okStat) ? '' : 'none';
                });
            }

            busca.addEventListener('input', filtrar);
            catSel.addEventListener('change', filtrar);
            statSel.addEventListener('change', filtrar);
        })();

        (function () {
            const btn = document.getElementById('btn-pdf-produtos');
            if (!btn) return;

            const colunas = <?= json_encode(array_values(array_filter(array_merge([
                'SKU', 'Produto', 'Categoria', 'Fornecedor', 'Estoque', 'Minimo', 'Preco Unitario', 'Status'
            ], $isAdmin ? ['Valor vendido 30 dias'] : [])))) ?>;

            const linhas = <?= json_encode(array_map(function ($p) use ($isAdmin, $vendasUltimoMes) {
                $qtd = $p->getQuantidadeEstoque();
                $min = $p->getQuantidadeMinima();
                $status = $qtd === 0 ? 'Sem Estoque' : ($qtd <= $min ? 'Critico' : ($qtd <= $min * 1.5 ? 'Atencao' : 'Normal'));
                $linha = [
                    $p->getSku(),
                    $p->getNome(),
                    $p->getCategoria() ? $p->getCategoria()->getNome() : '—',
                    $p->getFornecedor() ? $p->getFornecedor()->getNome() : '—',
                    $qtd,
                    $min,
                    'R$ ' . number_format($p->getPreco(), 2, ',', '.'),
                    $status,
                ];
                if ($isAdmin) {
                    $valor = $vendasUltimoMes[$p->getId()] ?? 0;
                    $linha[] = 'R$ ' . number_format($valor, 2, ',', '.');
                }
                return $linha;
            }, $produtos), JSON_UNESCAPED_UNICODE) ?>;

            btn.addEventListener('click', function () {
                gerarPDF({
                    titulo: 'Produtos',
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