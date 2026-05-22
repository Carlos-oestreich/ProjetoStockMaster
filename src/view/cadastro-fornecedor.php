<?php
/**
 * @var model\Fornecedor $fornecedor
 * @var int                                          $totalAlertas
 */
include_once 'template-cabecalho.php';

$isEdicao = $fornecedor->getId() !== null;
$action   = $isEdicao
    ? BASE_URL . "/fornecedores/{$fornecedor->getId()}/salvar"
    : BASE_URL . "/fornecedores/salvar";
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/fornecedores" class="text-primary">Fornecedores</a></li>
        <li class="breadcrumb-item active text-secondary">
            <?= $isEdicao ? htmlspecialchars($fornecedor->getNome()) : 'Novo Fornecedor' ?>
        </li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card border-secondary shadow" style="background:#1e293b;">
            <div class="card-header border-secondary py-3">
                <h4 class="card-title mb-0 fw-semibold text-white">
                    <i class="bi bi-<?= $isEdicao ? 'pencil-square' : 'plus-circle' ?> me-2 text-primary"></i>
                    <?= $isEdicao ? 'Editar Fornecedor' : 'Novo Fornecedor' ?>
                </h4>
            </div>
            <div class="card-body p-4">
                <form action="<?= $action ?>" method="POST" class="needs-validation" novalidate>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome"
                               value="<?= htmlspecialchars($fornecedor->getNome() ?? '') ?>"
                               placeholder="Razao social ou nome fantasia" required maxlength="200">
                        <div class="invalid-feedback">Informe o nome do fornecedor.</div>
                    </div>

                    <div class="mb-3">
                        <label for="cnpj" class="form-label">CNPJ</label>
                        <input type="text" class="form-control" id="cnpj" name="cnpj"
                               value="<?= htmlspecialchars($fornecedor->getCnpj() ?? '') ?>"
                               placeholder="00.000.000/0000-00" maxlength="18"
                               oninput="mascaraCNPJ(this)">
                        <div class="form-text text-secondary">Apenas numeros, 14 digitos.</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($fornecedor->getEmail() ?? '') ?>"
                                   placeholder="contato@empresa.com">
                            <div class="invalid-feedback">Informe um e-mail valido.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone"
                                   value="<?= htmlspecialchars($fornecedor->getTelefone() ?? '') ?>"
                                   placeholder="(00) 00000-0000" maxlength="15"
                                   oninput="mascaraTelefone(this)">
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ativo" name="ativo"
                                   value="1" <?= $fornecedor->getAtivo() !== false ? 'checked' : '' ?>>
                            <label class="form-check-label text-white" for="ativo">Fornecedor ativo</label>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg me-2"></i>Salvar
                        </button>
                        <a href="<?= BASE_URL ?>/fornecedores" class="btn btn-outline-secondary px-4">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'template-rodape.php'; ?>
