<?php
/**
 * @var model\Empresa $empresa
 * @var model\Usuario $usuario
 * @var model\Usuario $dono
 * @var string        $emailSuporte
 * @var int           $totalAlertas
 */
$usuarioModel = $usuario ?? null;
include_once 'template-cabecalho.php';

$perfil = $usuarioModel?->getPerfil() ?? ($_SESSION['usuario']['perfil'] ?? 'OPERADOR');
$isDono = $perfil === 'DONO';
$donoNome = $dono?->getNome() ?? '';
$donoEmail = $dono?->getEmail() ?? '';
?>

    <div class="mb-4">
        <h2 class="fw-bold text-white mb-1">Configuracoes</h2>
        <small class="text-secondary">Gerencie os dados da empresa e do seu perfil</small>
    </div>

    <div class="row g-4">

        <!-- DADOS DA EMPRESA -->
        <div class="col-12 col-lg-6">
            <div class="card border-secondary h-100" style="background:#1e293b;">
                <div class="card-header border-secondary py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-building text-primary fs-5"></i>
                        <h5 class="mb-0 fw-semibold text-white">Dados da Empresa</h5>
                    </div>
                    <?php if ($isDono): ?>
                        <button type="button" id="btn-editar-empresa" class="btn btn-sm btn-outline-primary"
                                onclick="ativarEdicao('empresa')">
                            <i class="bi bi-pencil me-1"></i>Editar
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/configuracoes/salvar-empresa" method="POST"
                          enctype="multipart/form-data" class="needs-validation" novalidate>

                        <?php if ($empresa?->getLogo()): ?>
                            <?php
                            $logo = $empresa->getLogo();
                            $logoUrl = preg_match('/^https?:\\/\\//', $logo) ? $logo : (BASE_URL . $logo);
                            ?>
                            <div class="mb-3 text-center">
                                <img src="<?= htmlspecialchars($logoUrl) ?>"
                                     alt="Logo" class="rounded-3"
                                     style="max-height:80px;max-width:200px;object-fit:contain;">
                                <div class="form-text text-secondary mt-1">Logo atual</div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3" data-secao="empresa">
                            <label for="nome_empresa" class="form-label">Nome da Empresa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo-bloqueado" id="nome_empresa" name="nome_empresa"
                                   value="<?= htmlspecialchars($empresa?->getNome() ?? '') ?>"
                                   required placeholder="Razao social ou nome fantasia" readonly>
                            <div class="invalid-feedback">Informe o nome da empresa.</div>
                        </div>

                        <div class="mb-3" data-secao="empresa">
                            <label for="cnpj_empresa" class="form-label">CNPJ</label>
                            <input type="text" class="form-control campo-bloqueado" id="cnpj_empresa" name="cnpj_empresa"
                                   value="<?= htmlspecialchars($empresa?->getCnpj() ?? '') ?>"
                                   placeholder="00.000.000/0000-00" maxlength="18"
                                   oninput="mascaraCNPJ(this)" readonly>
                        </div>

                        <div class="row g-3 mb-3" data-secao="empresa">
                            <div class="col-md-6">
                                <label for="email_empresa" class="form-label">E-mail</label>
                                <input type="email" class="form-control campo-bloqueado" id="email_empresa" name="email_empresa"
                                       value="<?= htmlspecialchars($empresa?->getEmail() ?? '') ?>"
                                       placeholder="contato@empresa.com" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="telefone_empresa" class="form-label">Telefone</label>
                                <input type="text" class="form-control campo-bloqueado" id="telefone_empresa" name="telefone_empresa"
                                       value="<?= htmlspecialchars($empresa?->getTelefone() ?? '') ?>"
                                       placeholder="(00) 00000-0000" maxlength="15"
                                       oninput="mascaraTelefone(this)" readonly>
                            </div>
                        </div>

                        <div class="mb-3" data-secao="empresa">
                            <label for="endereco_empresa" class="form-label">Endereco</label>
                            <input type="text" class="form-control campo-bloqueado" id="endereco_empresa" name="endereco_empresa"
                                   value="<?= htmlspecialchars($empresa?->getEndereco() ?? '') ?>"
                                   placeholder="Rua, numero, cidade - UF" readonly>
                        </div>

                        <div class="mb-3" data-secao="empresa">
                            <label class="form-label">Responsavel (Dono)</label>
                            <input type="text" class="form-control campo-bloqueado" value="<?= htmlspecialchars($donoNome ?: '—') ?>" readonly>
                        </div>

                        <div class="mb-3" data-secao="empresa">
                            <label class="form-label">E-mail do Responsavel</label>
                            <input type="email" class="form-control campo-bloqueado" value="<?= htmlspecialchars($donoEmail ?: '—') ?>" readonly>
                        </div>

                        <div class="mb-3" data-secao="empresa">
                            <label class="form-label">E-mail de Suporte (TI)</label>
                            <input type="email" class="form-control campo-bloqueado" value="<?= htmlspecialchars($emailSuporte ?? '') ?>" readonly>
                        </div>

                        <div class="mb-4" data-secao="empresa">
                            <label for="logo" class="form-label">Logo da Empresa</label>
                            <input type="file" class="form-control campo-bloqueado" id="logo" name="logo"
                                accept=".jpg,.jpeg,.png,.gif,.webp" disabled>
                            <div class="form-text text-secondary">JPG, PNG ou WEBP. Tamanho recomendado: 200x60px.</div>
                        </div>

                        <?php if ($isDono): ?>
                            <div class="d-flex gap-2">
                                <button type="submit" id="btn-salvar-empresa" class="btn btn-primary flex-fill d-none">
                                    <i class="bi bi-check-lg me-2"></i>Salvar dados da empresa
                                </button>
                                <button type="button" id="btn-cancelar-empresa" class="btn btn-outline-secondary flex-fill d-none"
                                        onclick="cancelarEdicao('empresa')">
                                    Cancelar
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- PERFIL DO USUARIO -->
        <div class="col-12 col-lg-6">
            <div class="card border-secondary" style="background:#1e293b;">
                <div class="card-header border-secondary py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-gear text-primary fs-5"></i>
                        <h5 class="mb-0 fw-semibold text-white">Meu Perfil</h5>
                    </div>
                    <button type="button" id="btn-editar-perfil" class="btn btn-sm btn-outline-primary"
                            onclick="ativarEdicao('perfil')">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </button>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/configuracoes/salvar-perfil" method="POST"
                          id="form-perfil" class="needs-validation" novalidate>

                        <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3"
                             style="background:rgba(79,70,229,.1);border:1px solid rgba(79,70,229,.2);">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                 style="width:48px;height:48px;background:#4f46e5;font-size:1.2rem;flex-shrink:0;">
                                <?= strtoupper(substr($usuarioModel?->getNome() ?? 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-semibold text-white"><?= htmlspecialchars($usuarioModel?->getNome() ?? '') ?></div>
                                <span class="badge" style="background:rgba(99,102,241,.2);color:#818cf8;font-size:.7rem;">
                                    <i class="bi bi-person-check me-1"></i><?= htmlspecialchars($perfil) ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3" data-secao="perfil">
                            <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo-bloqueado" id="nome" name="nome"
                                   value="<?= htmlspecialchars($usuarioModel?->getNome() ?? '') ?>"
                                   required readonly>
                            <div class="invalid-feedback">Informe o nome.</div>
                        </div>

                        <div class="mb-3" data-secao="perfil">
                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" class="form-control campo-bloqueado" id="email" name="email"
                                   value="<?= htmlspecialchars($usuarioModel?->getEmail() ?? '') ?>"
                                   required readonly>
                            <div class="invalid-feedback">Informe um e-mail valido.</div>
                        </div>

                        <div class="mb-3" data-secao="perfil">
                            <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                            <input type="text" class="form-control campo-bloqueado" id="cpf" name="cpf"
                                   value="<?= htmlspecialchars($usuarioModel?->getCpf() ?? '') ?>"
                                   placeholder="000.000.000-00" maxlength="14"
                                   oninput="mascaraCPF(this)" required readonly>
                            <div class="invalid-feedback">Informe o CPF.</div>
                        </div>

                        <hr class="border-secondary my-3">
                        <p class="text-secondary mb-3" style="font-size:.85rem;">
                            <i class="bi bi-info-circle me-1"></i>
                            Para alterar a senha, informe a senha atual.
                        </p>

                        <div class="mb-3" data-secao="perfil">
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control campo-bloqueado" id="senha_atual" name="senha_atual"
                                       placeholder="Senha atual" readonly>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="toggleSenha('senha_atual','o0')">
                                    <i class="bi bi-eye" id="o0"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3" data-secao="perfil">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control campo-bloqueado" id="senha" name="senha"
                                       placeholder="Minimo 8 caracteres" minlength="8" readonly>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="toggleSenha('senha','o1')">
                                    <i class="bi bi-eye" id="o1"></i>
                                </button>
                            </div>
                            <div id="msg-forca" class="form-text mt-1"></div>
                        </div>

                        <div class="mb-4" data-secao="perfil">
                            <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" class="form-control campo-bloqueado" id="confirma_senha" name="confirma_senha"
                                       placeholder="Repita a nova senha" minlength="8" readonly>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="toggleSenha('confirma_senha','o2')">
                                    <i class="bi bi-eye" id="o2"></i>
                                </button>
                            </div>
                            <div id="msg-confirma" class="form-text mt-1"></div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" id="btn-salvar-perfil" class="btn btn-success flex-fill d-none">
                                <i class="bi bi-check-lg me-2"></i>Salvar perfil
                            </button>
                            <button type="button" id="btn-cancelar-perfil" class="btn btn-outline-secondary flex-fill d-none"
                                    onclick="cancelarEdicao('perfil')">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info do sistema -->
            <div class="card border-secondary mt-3" style="background:#1e293b;">
                <div class="card-body p-4">
                    <h6 class="fw-semibold text-white mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>Informacoes do Sistema
                    </h6>
                    <div class="d-flex justify-content-between py-2 border-bottom border-secondary">
                        <span class="text-secondary">Versao</span>
                        <span class="text-white fw-medium">StockMaster 1.0</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom border-secondary">
                        <span class="text-secondary">Empresa</span>
                        <span class="text-white fw-medium"><?= htmlspecialchars($empresa?->getNome() ?? '---') ?></span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-secondary">Perfil</span>
                        <span class="badge" style="background:rgba(99,102,241,.2);color:#818cf8;">
                            <i class="bi bi-person-check me-1"></i><?= htmlspecialchars($perfil) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof monitorarForcaSenha === 'function') {
                monitorarForcaSenha('senha', 'msg-forca');
            }
            if (typeof monitorarConfirmaSenha === 'function') {
                monitorarConfirmaSenha('senha', 'confirma_senha', 'msg-confirma');
            }
        });
    </script>

<?php include_once 'template-rodape.php'; ?>