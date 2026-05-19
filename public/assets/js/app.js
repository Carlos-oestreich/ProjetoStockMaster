/**
 * StockMaster – app.js
 * Tema, validacao de senha, PDF, campos editaveis
 */

// ================================================================
// TEMA CLARO / ESCURO
// ================================================================
const TEMA_KEY = 'stockmaster_tema';

function aplicarTema(tema) {
    document.documentElement.setAttribute('data-bs-theme', tema);
    const icon = document.getElementById('icon-tema');
    if (icon) {
        icon.className = tema === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
    // Atualiza tabelas (Bootstrap nao propaga data-bs-theme automaticamente em tabelas)
    document.querySelectorAll('.table').forEach(t => {
        t.classList.toggle('table-dark',  tema === 'dark');
        t.classList.toggle('table-light', tema === 'light');
    });
}

function toggleTema() {
    const atual = localStorage.getItem(TEMA_KEY) || 'dark';
    const novo  = atual === 'dark' ? 'light' : 'dark';
    localStorage.setItem(TEMA_KEY, novo);
    aplicarTema(novo);
}

// Aplica tema salvo ao carregar
document.addEventListener('DOMContentLoaded', function () {
    const temaSalvo = localStorage.getItem(TEMA_KEY) || 'dark';
    aplicarTema(temaSalvo);

    if (document.querySelector('[data-secao]')) {
        registrarValoresIniciais();
    }

    // Validacao Bootstrap em todos os forms .needs-validation
    document.querySelectorAll('form.needs-validation').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    });

    // Auto-fechar alertas apos 4s
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e) {}
        }, 4000);
    });

    // Toggle sidebar mobile
    const btnToggle = document.getElementById('btn-toggle-sidebar');
    const sidebar   = document.getElementById('sidebar');
    if (btnToggle && sidebar) {
        btnToggle.addEventListener('click', () => sidebar.classList.toggle('d-none'));
    }

    // PDF relatorios
    const btnRelatorio = document.getElementById('btn-pdf-relatorio');
    if (btnRelatorio) {
        btnRelatorio.addEventListener('click', baixarRelatorioPdf);
    }
});

function baixarRelatorioPdf() {
    if (!window.relatorioDados) return;
    if (typeof gerarPDFRelatorio !== 'function') return;
    gerarPDFRelatorio(window.relatorioDados, window.stockmasterEmpresa || {});
}

// ================================================================
// VALIDACAO DE SENHA FORTE (client-side, espelha o PHP)
// ================================================================
function validarForcaSenha(senha) {
    const erros = [];
    if (senha.length < 8)              erros.push('minimo 8 caracteres');
    if (!/[A-Z]/.test(senha))          erros.push('uma letra maiuscula');
    if (!/[a-z]/.test(senha))          erros.push('uma letra minuscula');
    if (!/[0-9]/.test(senha))          erros.push('um numero');
    if (!/[\W_]/.test(senha))          erros.push('um simbolo (@, #, !, $...)');
    return erros;
}

/**
 * Conecta um input de senha a um elemento de feedback em tempo real.
 * @param {string} inputId   - id do input de senha
 * @param {string} feedbackId - id do elemento onde mostrar o feedback
 */
function monitorarForcaSenha(inputId, feedbackId) {
    const input    = document.getElementById(inputId);
    const feedback = document.getElementById(feedbackId);
    if (!input || !feedback) return;

    input.addEventListener('input', function () {
        const erros = validarForcaSenha(this.value);
        if (!this.value) {
            feedback.innerHTML = '';
            input.setCustomValidity('');
            return;
        }
        if (erros.length === 0) {
            feedback.innerHTML = '<span class="text-success"><i class="bi bi-shield-check me-1"></i>Senha forte!</span>';
            input.setCustomValidity('');
        } else {
            feedback.innerHTML = `<span class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Faltam: ${erros.join(', ')}.</span>`;
            input.setCustomValidity('fraca');
        }
    });
}

/**
 * Monitora se confirmar senha bate com a nova senha.
 */
function monitorarConfirmaSenha(novaSenhaId, confirmaId, feedbackId) {
    const confirma = document.getElementById(confirmaId);
    const feedback = document.getElementById(feedbackId);
    if (!confirma || !feedback) return;

    confirma.addEventListener('input', function () {
        const nova = document.getElementById(novaSenhaId)?.value;
        this.setCustomValidity('');
        if (!this.value) { feedback.innerHTML = ''; return; }
        if (this.value === nova) {
            feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>Senhas conferem.</span>';
        } else {
            feedback.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Senhas nao conferem.</span>';
            this.setCustomValidity('invalido');
        }
    });
}

// ================================================================
// CAMPOS EDITAVEIS (tela de configuracoes)
// Campos ficam travados, botao Editar destrava
// ================================================================
function ativarEdicao(secao) {
    const campos = document.querySelectorAll(`[data-secao="${secao}"] input, [data-secao="${secao}"] textarea, [data-secao="${secao}"] select`);
    const btn    = document.getElementById(`btn-editar-${secao}`);
    const btnSalvar = document.getElementById(`btn-salvar-${secao}`);
    const btnCancelar = document.getElementById(`btn-cancelar-${secao}`);

    campos.forEach(c => {
        c.removeAttribute('readonly');
        c.removeAttribute('disabled');
        c.classList.remove('campo-bloqueado');
    });
    if (btn)       btn.classList.add('d-none');
    if (btnSalvar) btnSalvar.classList.remove('d-none');
    if (btnCancelar) btnCancelar.classList.remove('d-none');
}

function cancelarEdicao(secao) {
    const campos = document.querySelectorAll(`[data-secao="${secao}"] input, [data-secao="${secao}"] textarea, [data-secao="${secao}"] select`);
    const btn    = document.getElementById(`btn-editar-${secao}`);
    const btnSalvar = document.getElementById(`btn-salvar-${secao}`);
    const btnCancelar = document.getElementById(`btn-cancelar-${secao}`);

    campos.forEach(c => {
        if (c.type === 'file') {
            c.value = '';
            c.setAttribute('disabled', 'disabled');
            c.classList.add('campo-bloqueado');
            return;
        }

        if (c.dataset.originalValue !== undefined) {
            c.value = c.dataset.originalValue;
        }

        if (c.tagName === 'SELECT') {
            c.setAttribute('disabled', 'disabled');
        } else {
            c.setAttribute('readonly', 'readonly');
        }
        c.classList.add('campo-bloqueado');
    });

    if (btn) btn.classList.remove('d-none');
    if (btnSalvar) btnSalvar.classList.add('d-none');
    if (btnCancelar) btnCancelar.classList.add('d-none');
}

function registrarValoresIniciais() {
    document.querySelectorAll('[data-secao]').forEach(secao => {
        secao.querySelectorAll('input, textarea, select').forEach(c => {
            if (c.type === 'file') return;
            if (c.dataset.originalValue === undefined) {
                c.dataset.originalValue = c.value;
            }
        });
    });
}

// ================================================================
// MASCARAS
// ================================================================
function mascaraCPF(input) {
    let v = input.value.replace(/\D/g,'').substring(0,11);
    v = v.replace(/(\d{3})(\d)/,'$1.$2')
        .replace(/(\d{3})(\d)/,'$1.$2')
        .replace(/(\d{3})(\d{1,2})$/,'$1-$2');
    input.value = v;
}
function mascaraCNPJ(input) {
    let v = input.value.replace(/\D/g,'').substring(0,14);
    v = v.replace(/^(\d{2})(\d)/,'$1.$2')
        .replace(/^(\d{2})\.(\d{3})(\d)/,'$1.$2.$3')
        .replace(/\.(\d{3})(\d)/,'.$1/$2')
        .replace(/(\d{4})(\d)/,'$1-$2');
    input.value = v;
}
function mascaraTelefone(input) {
    let v = input.value.replace(/\D/g,'').substring(0,11);
    v = v.length <= 10
        ? v.replace(/^(\d{2})(\d{4})(\d{0,4})/,'($1) $2-$3')
        : v.replace(/^(\d{2})(\d{5})(\d{0,4})/,'($1) $2-$3');
    input.value = v;
}

// ================================================================
// TOGGLE SENHA
// ================================================================
function toggleSenha(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (!input || !icon) return;
    input.type     = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

// ================================================================
// CONFIRMAR EXCLUSAO
// ================================================================
function confirmarExclusao(msg) {
    return confirm(msg || 'Confirma a exclusao? Esta acao nao pode ser desfeita.');
}

// ================================================================
// PDF com jsPDF + autoTable
// ================================================================
function formatarMoeda(valor) {
    const numero = typeof valor === 'number' ? valor : parseFloat(valor || 0);
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(numero || 0);
}
function gerarPDF(config) {
    /**
     * config = {
     *   titulo: string,
     *   empresa: { nome, cnpj, email, telefone, endereco, suporteEmail },
     *   logo: string|null,
     *   colunas: string[],
     *   linhas: any[][],
     *   orientacao?: 'landscape'|'portrait'
     * }
     */
    const { jsPDF } = window.jspdf;
    const orientacao = config.orientacao || 'landscape';
    const doc = new jsPDF({ orientation: orientacao, unit: 'mm', format: 'a4' });

    const corPrimaria  = [79, 70, 229];   // indigo
    const corSecundaria = [100, 116, 139]; // slate

    const empresa = config.empresa || window.stockmasterEmpresa || {};
    const logo = config.logo || empresa.logo || null;

    const pageWidth = doc.internal.pageSize.getWidth();

    const linha1 = [];
    if (empresa.cnpj) linha1.push('CNPJ: ' + empresa.cnpj);
    if (empresa.email) linha1.push('Email: ' + empresa.email);
    const linha2 = [];
    if (empresa.telefone) linha2.push('Tel: ' + empresa.telefone);
    if (empresa.endereco) linha2.push('Endereco: ' + empresa.endereco);
    const linha3 = [];
    if (empresa.suporteEmail) linha3.push('Suporte: ' + empresa.suporteEmail);

    const linhasInfo = [];
    if (linha1.length) linhasInfo.push(linha1.join(' | '));
    if (linha2.length) linhasInfo.push(linha2.join(' | '));
    if (linha3.length) linhasInfo.push(linha3.join(' | '));

    const headerHeight = 22 + (linhasInfo.length * 5);

    // Cabecalho
    doc.setFillColor(...corPrimaria);
    doc.rect(0, 0, pageWidth, headerHeight, 'F');
    doc.setTextColor(255, 255, 255);

    let textX = 14;
    if (logo) {
        try {
            const logoTipo = logo.includes('image/jpeg') || logo.includes('image/jpg') ? 'JPEG' : 'PNG';
            doc.addImage(logo, logoTipo, 12, 4, 22, 16);
            textX = 38;
        } catch (e) {
            textX = 14;
        }
    }

    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text(config.titulo, textX, 9);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text(empresa.nome || '—', textX, 15);

    if (linhasInfo.length > 0) {
        let infoY = 20;
        linhasInfo.forEach(linha => {
            doc.text(linha, textX, infoY, { maxWidth: pageWidth - textX - 14 });
            infoY += 5;
        });
    }

    // Data de geracao
    const agora = new Date().toLocaleString('pt-BR');
    doc.text('Gerado em: ' + agora, pageWidth - 14, 15, { align: 'right' });

    // Tabela
    doc.autoTable({
        head: [config.colunas],
        body: config.linhas,
        startY: headerHeight + 6,
        styles: { fontSize: 8, cellPadding: 3 },
        headStyles: {
            fillColor: corPrimaria,
            textColor: [255, 255, 255],
            fontStyle: 'bold',
        },
        alternateRowStyles: { fillColor: [248, 250, 252] },
        margin: { left: 14, right: 14 },
    });

    // Rodape
    const totalPaginas = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPaginas; i++) {
        doc.setPage(i);
        doc.setFontSize(7);
        doc.setTextColor(...corSecundaria);
        doc.text(
            `StockMaster – ${empresa.nome || 'Empresa'} | Pagina ${i} de ${totalPaginas}`,
            14, doc.internal.pageSize.getHeight() - 8
        );
    }

    const nomeArquivo = config.titulo.toLowerCase().replace(/\s+/g, '_') + '_' +
        new Date().toISOString().slice(0,10) + '.pdf';
    doc.save(nomeArquivo);
}

function gerarPDFRelatorio(dados, empresaInfo) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

    const corPrimaria  = [79, 70, 229];
    const corSecundaria = [100, 116, 139];
    const empresa = empresaInfo || window.stockmasterEmpresa || {};
    const logo = empresa.logo || null;

    const pageWidth = doc.internal.pageSize.getWidth();

    const linha1 = [];
    if (empresa.cnpj) linha1.push('CNPJ: ' + empresa.cnpj);
    if (empresa.email) linha1.push('Email: ' + empresa.email);
    const linha2 = [];
    if (empresa.telefone) linha2.push('Tel: ' + empresa.telefone);
    if (empresa.endereco) linha2.push('Endereco: ' + empresa.endereco);
    const linha3 = [];
    if (empresa.suporteEmail) linha3.push('Suporte: ' + empresa.suporteEmail);

    const linhasInfo = [];
    if (linha1.length) linhasInfo.push(linha1.join(' | '));
    if (linha2.length) linhasInfo.push(linha2.join(' | '));
    if (linha3.length) linhasInfo.push(linha3.join(' | '));

    const headerHeight = 22 + (linhasInfo.length * 5);

    doc.setFillColor(...corPrimaria);
    doc.rect(0, 0, pageWidth, headerHeight, 'F');
    doc.setTextColor(255, 255, 255);

    let textX = 14;
    if (logo) {
        try {
            const logoTipo = logo.includes('image/jpeg') || logo.includes('image/jpg') ? 'JPEG' : 'PNG';
            doc.addImage(logo, logoTipo, 12, 4, 20, 16);
            textX = 36;
        } catch (e) {
            textX = 14;
        }
    }

    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text('Relatorio Gerencial', textX, 9);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text(empresa.nome || '—', textX, 15);

    if (linhasInfo.length > 0) {
        let infoY = 20;
        linhasInfo.forEach(linha => {
            doc.text(linha, textX, infoY, { maxWidth: pageWidth - textX - 14 });
            infoY += 5;
        });
    }

    const agora = new Date().toLocaleString('pt-BR');
    doc.text('Gerado em: ' + agora, pageWidth - 14, 15, { align: 'right' });

    let cursorY = headerHeight + 6;

    // Resumo 30 dias
    doc.setTextColor(20, 20, 20);
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Resumo (Ultimos 30 dias)', 14, cursorY);
    cursorY += 3;

    const resumo = dados?.resumo || {};
    const linhasResumo = [
        ['Total de Entradas', resumo.totalEntradas ?? 0],
        ['Total de Saidas', resumo.totalSaidas ?? 0],
        ['SKUs Cadastrados', resumo.skus ?? 0],
        ['Valor Total do Estoque', formatarMoeda(resumo.valorTotalEstoque ?? 0)],
        ['Valor de Entrada (30 dias)', formatarMoeda(resumo.valorEntradas ?? 0)],
        ['Valor de Saida (30 dias)', formatarMoeda(resumo.valorSaidas ?? 0)],
    ];

    doc.autoTable({
        head: [['Indicador', 'Valor']],
        body: linhasResumo,
        startY: cursorY + 2,
        styles: { fontSize: 9, cellPadding: 2 },
        headStyles: { fillColor: corPrimaria, textColor: [255, 255, 255] },
        margin: { left: 14, right: 14 },
    });
    cursorY = doc.lastAutoTable.finalY + 6;

    // Top 5 produtos mais vendidos
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Top 5 Produtos Mais Vendidos', 14, cursorY);
    cursorY += 3;

    const safeArray = (valor) => {
        if (Array.isArray(valor)) return valor;
        if (valor && typeof valor === 'object') return Object.values(valor);
        return [];
    };

    const topProdutos = safeArray(dados?.topProdutos).map(item => [
        item.nome || '—',
        item.quantidade ?? 0,
        formatarMoeda(item.valor ?? 0),
    ]);
    if (topProdutos.length === 0) {
        topProdutos.push(['Sem dados', '-', '-']);
    }

    doc.autoTable({
        head: [['Produto', 'Qtd Vendida', 'Valor']],
        body: topProdutos,
        startY: cursorY + 2,
        styles: { fontSize: 9, cellPadding: 2 },
        headStyles: { fillColor: corPrimaria, textColor: [255, 255, 255] },
        margin: { left: 14, right: 14 },
    });
    cursorY = doc.lastAutoTable.finalY + 6;

    // Valor vendido por categoria
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Valor Vendido por Categoria', 14, cursorY);
    cursorY += 3;

    const valorCategorias = safeArray(dados?.valorPorCategoria).map(item => [
        item.categoria || 'Sem categoria',
        formatarMoeda(item.valor ?? 0),
    ]);
    if (valorCategorias.length === 0) {
        valorCategorias.push(['Sem dados', '-']);
    }

    doc.autoTable({
        head: [['Categoria', 'Valor Vendido']],
        body: valorCategorias,
        startY: cursorY + 2,
        styles: { fontSize: 9, cellPadding: 2 },
        headStyles: { fillColor: corPrimaria, textColor: [255, 255, 255] },
        margin: { left: 14, right: 14 },
    });
    cursorY = doc.lastAutoTable.finalY + 6;

    // Itens mais vendidos por categoria
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Itens Mais Vendidos por Categoria', 14, cursorY);
    cursorY += 3;

    const topCategoria = safeArray(dados?.topPorCategoria).map(item => [
        item.categoria || 'Sem categoria',
        item.produto || '—',
        item.quantidade ?? 0,
        formatarMoeda(item.valor ?? 0),
    ]);
    if (topCategoria.length === 0) {
        topCategoria.push(['Sem dados', '-', '-', '-']);
    }

    doc.autoTable({
        head: [['Categoria', 'Produto', 'Qtd Vendida', 'Valor']],
        body: topCategoria,
        startY: cursorY + 2,
        styles: { fontSize: 9, cellPadding: 2 },
        headStyles: { fillColor: corPrimaria, textColor: [255, 255, 255] },
        margin: { left: 14, right: 14 },
    });

    // Tabela completa de estoque
    doc.addPage('a4', 'portrait');
    doc.setTextColor(20, 20, 20);
    doc.setFontSize(11);
    doc.setFont('helvetica', 'bold');
    doc.text('Posicao Atual do Estoque', 14, 16);

    const estoque = safeArray(dados?.estoque).map(item => [
        item.nome || '—',
        item.sku || '—',
        item.categoria || '—',
        item.fornecedor || '—',
        item.quantidade ?? 0,
        formatarMoeda(item.preco ?? 0),
        formatarMoeda(item.valorTotal ?? 0),
    ]);
    if (estoque.length === 0) {
        estoque.push(['Sem dados', '-', '-', '-', '-', '-', '-']);
    }

    doc.autoTable({
        head: [['Produto', 'SKU', 'Categoria', 'Fornecedor', 'Qtd', 'Valor Unit.', 'Valor Total']],
        body: estoque,
        startY: 20,
        styles: { fontSize: 7, cellPadding: 2 },
        headStyles: { fillColor: corPrimaria, textColor: [255, 255, 255] },
        margin: { left: 14, right: 14 },
    });

    // Rodape com paginação
    const totalPaginas = doc.internal.getNumberOfPages();
    for (let i = 1; i <= totalPaginas; i++) {
        doc.setPage(i);
        doc.setFontSize(7);
        doc.setTextColor(...corSecundaria);
        doc.text(
            `StockMaster – ${empresa.nome || 'Empresa'} | Pagina ${i} de ${totalPaginas}`,
            14, doc.internal.pageSize.getHeight() - 8
        );
    }

    const nomeArquivo = 'relatorio_gerencial_' + new Date().toISOString().slice(0,10) + '.pdf';
    doc.save(nomeArquivo);
}