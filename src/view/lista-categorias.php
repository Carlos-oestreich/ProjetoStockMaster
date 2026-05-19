<?php
/** @var model\Categoria[] $categorias */
include_once 'template-cabecalho.php';
?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Categorias</h2>
            <small class="text-secondary"><?= count($categorias) ?> categoria(s)</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-categorias">
                <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
            </button>
            <a href="<?= BASE_URL ?>/categorias/novo" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Nova Categoria
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filtro-busca" class="form-control" placeholder="Buscar categoria...">
            </div>
        </div>
    </div>

    <div class="table-stockmaster">
        <table class="table table-dark table-hover mb-0" id="tabela-categorias">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Setor</th>
                <th>Código Interno</th>
                <th>Status</th>
                <th class="text-end">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($categorias)): ?>
                <tr>
                    <td colspan="6" class="text-center text-secondary py-5">
                        <i class="bi bi-tags fs-2 d-block mb-2"></i>
                        Nenhuma categoria cadastrada.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($categorias as $cat): ?>
                    <tr data-busca="<?= strtolower(htmlspecialchars($cat->getNome() . ' ' . $cat->getSetor())) ?>">
                        <td class="text-secondary"><?= $cat->getId() ?></td>
                        <td class="fw-medium text-white"><?= htmlspecialchars($cat->getNome()) ?></td>
                        <td class="text-secondary"><?= htmlspecialchars($cat->getSetor() ?? '—') ?></td>
                        <td><code class="text-secondary"><?= htmlspecialchars($cat->getCodigoInterno() ?? '—') ?></code></td>
                        <td>
                            <?php if ($cat->getAtivo()): ?>
                                <span class="badge badge-normal">Ativo</span>
                            <?php else: ?>
                                <span class="badge badge-sem-estoque">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>/categorias/<?= $cat->getId() ?>"
                               class="btn btn-icon btn-outline-primary me-1" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= BASE_URL ?>/categorias/<?= $cat->getId() ?>/deletar"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirmarExclusao('Remover a categoria «<?= htmlspecialchars($cat->getNome(), ENT_QUOTES) ?>»?')">
                                <button class="btn btn-icon btn-outline-danger" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('filtro-busca').addEventListener('input', function () {
            const termo = this.value.toLowerCase();
            document.querySelectorAll('#tabela-categorias tbody tr[data-busca]').forEach(tr => {
                tr.style.display = tr.dataset.busca.includes(termo) ? '' : 'none';
            });
        });

        (function () {
            const btn = document.getElementById('btn-pdf-categorias');
            if (!btn) return;

            const colunas = <?= json_encode(['ID', 'Nome', 'Setor', 'Codigo Interno', 'Status']) ?>;
            const linhas = <?= json_encode(array_map(function ($cat) {
                return [
                    $cat->getId(),
                    $cat->getNome(),
                    $cat->getSetor() ?? '—',
                    $cat->getCodigoInterno() ?? '—',
                    $cat->getAtivo() ? 'Ativo' : 'Inativo',
                ];
            }, $categorias), JSON_UNESCAPED_UNICODE) ?>;

            btn.addEventListener('click', function () {
                gerarPDF({
                    titulo: 'Categorias',
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