</main><!-- /main -->
</div><!-- /conteúdo -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.4/dist/jspdf.plugin.autotable.min.js"></script>
<?php
$empresaSessao = $_SESSION['usuario'] ?? [];
$logoBase64 = '';
$logoPathOrUrl = $empresaSessao['empresaLogo'] ?? '';

if (!empty($logoPathOrUrl)) {
    if (filter_var($logoPathOrUrl, FILTER_VALIDATE_URL)) {
        $imgData = @file_get_contents($logoPathOrUrl);
        if ($imgData !== false) {
            $mime = 'image/png';
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if ($finfo) {
                    $mime = finfo_buffer($finfo, $imgData) ?: $mime;
                    finfo_close($finfo);
                }
            }
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode($imgData);
        }
    } else {
        $logoPath = dirname(__DIR__, 2) . '/public' . $logoPathOrUrl;
        if (is_file($logoPath)) {
            $mime = function_exists('mime_content_type') ? mime_content_type($logoPath) : 'image/png';
            $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    }
}
?>
<script>
    window.stockmasterEmpresa = {
        nome: <?= json_encode($empresaSessao['empresaNome'] ?? '') ?>,
        cnpj: <?= json_encode($empresaSessao['empresaCnpj'] ?? '') ?>,
        email: <?= json_encode($empresaSessao['empresaEmail'] ?? '') ?>,
        telefone: <?= json_encode($empresaSessao['empresaTelefone'] ?? '') ?>,
        endereco: <?= json_encode($empresaSessao['empresaEndereco'] ?? '') ?>,
        suporteEmail: <?= json_encode(defined('SUPORTE_EMAIL') ? SUPORTE_EMAIL : '') ?>,
        logo: <?= json_encode($logoBase64) ?>
    };
</script>
<?php
$appJsPath = dirname(__DIR__, 2) . '/public/assets/js/app.js';
$appJsVer = is_file($appJsPath) ? filemtime($appJsPath) : time();
?>
<script src="<?= BASE_URL ?>/assets/js/app.js?v=<?= $appJsVer ?>"></script>
<script>
    // Toggle sidebar mobile
    const btnToggle = document.getElementById('btn-toggle-sidebar');
    const sidebar   = document.getElementById('sidebar');
    if (btnToggle && sidebar) {
        btnToggle.addEventListener('click', () => sidebar.classList.toggle('d-none'));
    }

    // Auto-fechar alertas após 4s
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            bsAlert.close();
        }, 4000);
    });
</script>
</body>
</html>