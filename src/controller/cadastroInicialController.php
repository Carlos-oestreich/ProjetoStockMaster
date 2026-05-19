<?php

namespace controller;

use Exception;
use dao\UsuarioDAO;
use dao\EmpresaDAO;
use model\Usuario;
use model\Empresa;

class cadastroInicialController
{
    public function index()
    {
        // Nao bloqueia mais se ja tem dono — permite criar multiplas empresas
        require __DIR__ . '/../view/cadastro-inicial.php';
    }

    public function salvar()
    {
        try {
            $nomeUsuario  = filter_input(INPUT_POST, 'nome',            FILTER_SANITIZE_SPECIAL_CHARS);
            $email        = filter_input(INPUT_POST, 'email',           FILTER_SANITIZE_EMAIL);
            $cpf          = filter_input(INPUT_POST, 'cpf',             FILTER_SANITIZE_SPECIAL_CHARS);
            $matricula    = filter_input(INPUT_POST, 'matricula',       FILTER_SANITIZE_SPECIAL_CHARS);
            $senha        = filter_input(INPUT_POST, 'senha',           FILTER_SANITIZE_SPECIAL_CHARS);
            $confirma     = filter_input(INPUT_POST, 'confirma_senha',  FILTER_SANITIZE_SPECIAL_CHARS);
            $nomeEmpresa  = filter_input(INPUT_POST, 'nome_empresa',    FILTER_SANITIZE_SPECIAL_CHARS);
            $cnpjEmpresa  = filter_input(INPUT_POST, 'cnpj_empresa',    FILTER_SANITIZE_SPECIAL_CHARS);
            $emailEmpresa = filter_input(INPUT_POST, 'email_empresa',   FILTER_SANITIZE_EMAIL);
            $telEmpresa   = filter_input(INPUT_POST, 'telefone_empresa',FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($nomeUsuario) || empty($email) || empty($senha) || empty($nomeEmpresa)) {
                throw new Exception('Preencha todos os campos obrigatorios.');
            }
            if ($senha !== $confirma) {
                throw new Exception('As senhas nao conferem.');
            }

            // Valida forca da senha do dono (ele mesmo define, entao ja tem que ser forte)
            ConfiguracaoController::validarForcaSenha($senha);

            // Valida CPF
            $cpfLimpo = preg_replace('/\D/', '', $cpf ?? '');
            if (empty($cpfLimpo)) {
                throw new Exception('CPF e obrigatorio.');
            }
            if (strlen($cpfLimpo) !== 11) {
                throw new Exception('CPF invalido. Informe os 11 digitos.');
            }

            // Email ja existe?
            if (UsuarioDAO::buscarPorEmail($email)) {
                throw new Exception('Este e-mail ja esta cadastrado no sistema.');
            }

            // CPF ja existe?
            if (UsuarioDAO::buscarPorCpf($cpfLimpo)) {
                throw new Exception('Este CPF ja esta cadastrado.');
            }

            // Cria a empresa
            $empresa = new Empresa();
            $empresa->setNome($nomeEmpresa);
            $empresa->setCnpj(preg_replace('/\D/', '', $cnpjEmpresa ?? '') ?: null);
            $empresa->setEmail($emailEmpresa ?: null);
            $empresa->setTelefone($telEmpresa ?: null);
            EmpresaDAO::salvar($empresa);

            // Cria o Dono vinculado a empresa
            $usuario = new Usuario();
            $usuario->setNome($nomeUsuario);
            $usuario->setEmail($email);
            $usuario->setSenha(password_hash($senha, PASSWORD_DEFAULT));
            $usuario->setPerfil('DONO');
            $usuario->setMatricula($matricula ?? '');
            $usuario->setCpf($cpfLimpo ?: null);
            $usuario->setAtivo(true);
            $usuario->setSenhaTemporaria(false); // Dono define a propria senha
            $usuario->setEmpresa($empresa);
            UsuarioDAO::salvar($usuario);

            $_SESSION['flash'] = [
                'tipo'     => 'success',
                'mensagem' => 'Empresa cadastrada! Faca login para continuar.'
            ];
            header('Location: ' . BASE_URL . '/login');
            exit;

        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/cadastro-inicial');
            exit;
        }
    }
}