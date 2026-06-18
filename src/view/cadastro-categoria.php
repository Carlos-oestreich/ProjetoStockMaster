<?php
/**
 * @var model\Categoria $categoria
 * @var int                                         $totalAlertas
 */
include_once 'template-cabecalho.php';

$isEdicao = $categoria->getId() !== null;
$action   = $isEdicao
    ? BASE_URL . "/categorias/{$categoria->getId()}/salvar"
    : BASE_URL . "/categorias/salvar";
?>

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/categorias" class="text-primary">Categorias</a></li>
            <li class="breadcrumb-item active text-secondary">
                <?= $isEdicao ? htmlspecialchars($categoria->getNome()) : 'Nova Categoria' ?>
            </li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            <div class="card border-secondary shadow" style="background:#1e293b;">
                <div class="card-header border-secondary py-3">
                    <h4 class="card-title mb-0 fw-semibold text-white">
                        <i class="bi bi-<?= $isEdicao ? 'pencil-square' : 'plus-circle' ?> me-2 text-primary"></i>
                        <?= $isEdicao ? 'Editar Categoria' : 'Nova Categoria' ?>
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?= $action ?>" method="POST" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   value="<?= htmlspecialchars($categoria->getNome() ?? '') ?>"
                                   placeholder="Ex: Eletronicos" required maxlength="100">
                            <div class="invalid-feedback">Informe o nome da categoria.</div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2"
                                      placeholder="Descricao da categoria"><?= htmlspecialchars($categoria->getDescricao() ?? '') ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="setor" class="form-label">Setor</label>
                                <input type="text" class="form-control" id="setor" name="setor"
                                       value="<?= htmlspecialchars($categoria->getSetor() ?? '') ?>"
                                       placeholder="Ex: Tecnologia">
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_interno" class="form-label">Codigo Interno</label>
                                <input type="text" class="form-control" id="codigo_interno" name="codigo_interno"
                                       value="<?= htmlspecialchars($categoria->getCodigoInterno() ?? '') ?>"
                                       placeholder="Ex: CAT-001">
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ativo" name="ativo"
                                       value="1" <?= $categoria->getAtivo() !== false ? 'checked' : '' ?>>
                                <label class="form-check-label text-white" for="ativo">Categoria ativa</label>
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-lg me-2"></i>Salvar
                            </button>
                            <a href="<?= BASE_URL ?>/categorias" class="btn btn-outline-secondary px-4">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include_once 'template-rodape.php'; ?>