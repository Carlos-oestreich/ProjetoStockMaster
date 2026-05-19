<?php
/**
 * @var model\Produto     $produto
 * @var model\Categoria[] $categorias
 * @var model\Fornecedor[] $fornecedores
 * @var int $totalAlertas
 */
include_once 'template-cabecalho.php';

$isEdicao = $produto->getId() !== null;
$action   = $isEdicao
    ? BASE_URL . "/produtos/{$produto->getId()}/salvar"
    : BASE_URL . "/produtos/salvar";
?>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/produtos" class="text-primary">Produtos</a></li>
            <li class="breadcrumb-item active text-secondary">
                <?= $isEdicao ? htmlspecialchars($produto->getNome()) : 'Novo Produto' ?>
            </li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <div class="card border-secondary shadow" style="background:#1e293b;">
                <div class="card-header border-secondary py-3">
                    <h4 class="card-title mb-0 fw-semibold text-white">
                        <i class="bi bi-<?= $isEdicao ? 'pencil-square' : 'plus-circle' ?> me-2 text-primary"></i>
                        <?= $isEdicao ? 'Editar Produto' : 'Novo Produto' ?>
                    </h4>
                </div>

                <div class="card-body p-4">
                    <form action="<?= $action ?>" method="POST" class="needs-validation" novalidate>

                        <!-- SKU + Nome -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="sku" name="sku"
                                       value="<?= htmlspecialchars($produto->getSku() ?? '') ?>"
                                       placeholder="Ex: ELT-001" required maxlength="50">
                                <div class="invalid-feedback">Informe o SKU.</div>
                            </div>
                            <div class="col-md-8">
                                <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                       value="<?= htmlspecialchars($produto->getNome() ?? '') ?>"
                                       placeholder="Nome do produto" required maxlength="200">
                                <div class="invalid-feedback">Informe o nome do produto.</div>
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao"
                                      rows="2" placeholder="Descrição breve do produto"><?= htmlspecialchars($produto->getDescricao() ?? '') ?></textarea>
                        </div>

                        <!-- Marca + Preço -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca"
                                       value="<?= htmlspecialchars($produto->getMarca() ?? '') ?>"
                                       placeholder="Ex: Dell, Samsung...">
                            </div>
                            <div class="col-md-6">
                                <label for="preco" class="form-label">Preço (R$) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" id="preco" name="preco"
                                           value="<?= $produto->getPreco() ?? '' ?>"
                                           step="0.01" min="0" required placeholder="0,00">
                                    <div class="invalid-feedback">Informe um preço válido.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantidade Mínima + Estoque Inicial -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="quantidade_minima" class="form-label">Qtd. Mínima <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantidade_minima" name="quantidade_minima"
                                       value="<?= $produto->getQuantidadeMinima() ?? 0 ?>"
                                       min="0" required>
                                <div class="form-text text-secondary">Alerta abaixo desse valor.</div>
                                <div class="invalid-feedback">Informe a quantidade mínima.</div>
                            </div>

                            <?php if (!$isEdicao): ?>
                                <div class="col-md-6">
                                    <label for="quantidade_estoque" class="form-label">Estoque Inicial</label>
                                    <input type="number" class="form-control" id="quantidade_estoque" name="quantidade_estoque"
                                           value="0" min="0">
                                    <div class="form-text text-secondary">Registra uma entrada automática.</div>
                                </div>
                            <?php else: ?>
                                <div class="col-md-6">
                                    <label class="form-label">Estoque Atual</label>
                                    <div class="form-control bg-secondary text-white fw-bold">
                                        <?= $produto->getQuantidadeEstoque() ?> unidades
                                    </div>
                                    <div class="form-text text-secondary">Altere via Movimentações.</div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Categoria + Fornecedor -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="categoria_id" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($categorias as $cat): ?>
                                        <option value="<?= $cat->getId() ?>"
                                            <?= $produto->getCategoria()?->getId() == $cat->getId() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat->getNome()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Selecione uma categoria.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                                <select class="form-select" id="fornecedor_id" name="fornecedor_id">
                                    <option value="">Nenhum</option>
                                    <?php foreach ($fornecedores as $forn): ?>
                                        <option value="<?= $forn->getId() ?>"
                                            <?= $produto->getFornecedor()?->getId() == $forn->getId() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($forn->getNome()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-2"></i>Salvar
                            </button>
                            <a href="<?= BASE_URL ?>/produtos" class="btn btn-outline-secondary px-4">
                                Cancelar
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

<?php include_once 'template-rodape.php'; ?>