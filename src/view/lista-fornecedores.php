<?php
/** @var model\Fornecedor[] $fornecedores */
include_once 'template-cabecalho.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-white mb-1">Fornecedores</h2>
            <small class="text-secondary"><?= count($fornecedores) ?> fornecedor(es)</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-light d-flex align-items-center gap-2" id="btn-pdf-fornecedores">
                <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
            </button>
            <a href="<?= BASE_URL ?>/fornecedores/novo" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Novo Fornecedor
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="filtro-busca" class="form-control" placeholder="Buscar fornecedor...">
            </div>
        </div>
    </div>

    <div class="table-stockmaster">
        <table class="table table-dark table-hover mb-0" id="tabela-fornecedores">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Status</th>
                <th class="text-end">Acoes</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($fornecedores)): ?>
                <tr>
                    <td colspan="7" class="text-center text-secondary py-5">
                        <i class="bi bi-truck fs-2 d-block mb-2"></i>
                        Nenhum fornecedor cadastrado.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($fornecedores as $f): ?>
                    <tr data-busca="<?= strtolower(htmlspecialchars($f->getNome() . ' ' . $f->getCnpj() . ' ' . $f->getEmail())) ?>">
                        <td class="text-secondary"><?= $f->getId() ?></td>
                        <td class="fw-medium text-white"><?= htmlspecialchars($f->getNome()) ?></td>
                        <td><code class="text-secondary"><?= htmlspecialchars($f->getCnpj() ?? '—') ?></code></td>
                        <td class="text-secondary"><?= htmlspecialchars($f->getEmail() ?? '—') ?></td>
                        <td class="text-secondary"><?= htmlspecialchars($f->getTelefone() ?? '—') ?></td>
                        <td>
                            <?php if ($f->getAtivo()): ?>
                                <span class="badge badge-normal">Ativo</span>
                            <?php else: ?>
                                <span class="badge badge-sem-estoque">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>/fornecedores/<?= $f->getId() ?>"
                               class="btn btn-icon btn-outline-primary me-1" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= BASE_URL ?>/fornecedores/<?= $f->getId() ?>/deletar"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirmarExclusao('Remover o fornecedor')">
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
            document.querySelectorAll('#tabela-fornecedores tbody tr[data-busca]').forEach(tr => {
                tr.style.display = tr.dataset.busca.includes(termo) ? '' : 'none';
            });
        });

        (function () {
            const btn = document.getElementById('btn-pdf-fornecedores');
            if (!btn) return;

            const colunas = <?= json_encode(['ID', 'Nome', 'CNPJ', 'E-mail', 'Telefone', 'Status']) ?>;
            const linhas = <?= json_encode(array_map(function ($f) {
                return [
                    $f->getId(),
                    $f->getNome(),
                    $f->getCnpj() ?? '—',
                    $f->getEmail() ?? '—',
                    $f->getTelefone() ?? '—',
                    $f->getAtivo() ? 'Ativo' : 'Inativo',
                ];
            }, $fornecedores), JSON_UNESCAPED_UNICODE) ?>;

            btn.addEventListener('click', function () {
                gerarPDF({
                    titulo: 'Fornecedores',
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