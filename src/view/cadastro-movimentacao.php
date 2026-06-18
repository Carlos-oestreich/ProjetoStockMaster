<?php
/** @var model\Produto[] $produtos */
include_once 'template-cabecalho.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/movimentacoes" class="text-primary">Movimentacoes</a></li>
        <li class="breadcrumb-item active text-secondary">Nova Movimentacao</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card border-secondary shadow" style="background:#1e293b;">
            <div class="card-header border-secondary py-3">
                <h4 class="card-title mb-0 fw-semibold text-white">
                    <i class="bi bi-arrow-left-right me-2 text-primary"></i>
                    Registrar Movimentacao
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/movimentacoes/salvar" method="POST"
                      class="needs-validation" novalidate>

                    <!-- Tipo -->
                    <div class="mb-4">
                        <label class="form-label">Tipo <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="radio" name="tipo"
                                       id="tipo-entrada" value="ENTRADA" required checked>
                                <label class="form-check-label text-success fw-medium" for="tipo-entrada">
                                    <i class="bi bi-arrow-down-circle me-1"></i> Entrada
                                </label>
                            </div>
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="radio" name="tipo"
                                       id="tipo-saida" value="SAIDA">
                                <label class="form-check-label text-danger fw-medium" for="tipo-saida">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Saida
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Produto -->
                    <div class="mb-3">
                        <label for="produto_id" class="form-label">Produto <span class="text-danger">*</span></label>
                        <select class="form-select" id="produto_id" name="produto_id"
                                required onchange="atualizarEstoque(this)">
                            <option value="">Selecione o produto...</option>
                            <?php foreach ($produtos as $p): ?>
                                <option value="<?= $p->getId() ?>"
                                        data-estoque="<?= $p->getQuantidadeEstoque() ?>"
                                        data-minimo="<?= $p->getQuantidadeMinima() ?>">
                                    <?= htmlspecialchars($p->getNome()) ?>
                                    (SKU: <?= htmlspecialchars($p->getSku()) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">Selecione um produto.</div>
                    </div>

                    <!-- Informacao de estoque atual (JS) -->
                    <div id="info-estoque" class="alert alert-secondary d-none mb-3 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Estoque atual:</span>
                            <strong id="txt-estoque-atual">—</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Estoque minimo:</span>
                            <strong id="txt-estoque-minimo">—</strong>
                        </div>
                    </div>

                    <!-- Quantidade -->
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade"
                               min="1" required placeholder="0">
                        <div class="invalid-feedback">Informe uma quantidade valida (minimo 1).</div>
                    </div>

                    <!-- Observacao -->
                    <div class="mb-4">
                        <label for="observacao" class="form-label">Observacao</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="2"
                                  placeholder="Ex: Compra mensal, entrega ao setor X..."></textarea>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg me-2"></i>Registrar
                        </button>
                        <a href="<?= BASE_URL ?>/movimentacoes" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function atualizarEstoque(select) {
        const opt     = select.options[select.selectedIndex];
        const infoDiv = document.getElementById('info-estoque');
        if (!opt.value) { infoDiv.classList.add('d-none'); return; }
        document.getElementById('txt-estoque-atual').textContent  = opt.dataset.estoque + ' un';
        document.getElementById('txt-estoque-minimo').textContent = opt.dataset.minimo + ' un';
        infoDiv.classList.remove('d-none');
    }
</script>

<?php include_once 'template-rodape.php'; ?>
