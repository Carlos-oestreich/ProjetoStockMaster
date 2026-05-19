<?php

namespace controller;

use Exception;
use dao\UsuarioDAO;

class alterarSenhaController
{
    public function index()
    {
        requerLogin();
        $paginaAtiva  = '';
        $tituloPagina = 'Alterar Senha';
        require __DIR__ . '/../view/alterar-senha.php';
    }

    public function salvar()
    {
        requerLogin();
        try {
            $senhaAtual = filter_input(INPUT_POST, 'senha_atual',    FILTER_SANITIZE_SPECIAL_CHARS);
            $novaSenha  = filter_input(INPUT_POST, 'nova_senha',     FILTER_SANITIZE_SPECIAL_CHARS);
            $confirma   = filter_input(INPUT_POST, 'confirma_senha', FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($senhaAtual) || empty($novaSenha) || empty($confirma)) {
                throw new Exception('Preencha todos os campos.');
            }
            if ($novaSenha !== $confirma) {
                throw new Exception('As senhas nao conferem.');
            }

            $usuario = UsuarioDAO::buscarPorId($_SESSION['usuario']['id']);
            if (!$usuario) throw new Exception('Usuario nao encontrado.');

            if (!password_verify($senhaAtual, $usuario->getSenha())) {
                throw new Exception('Senha atual incorreta.');
            }
            if (password_verify($novaSenha, $usuario->getSenha())) {
                throw new Exception('A nova senha nao pode ser igual a senha atual.');
            }

            // Valida forca da senha
            ConfiguracaoController::validarForcaSenha($novaSenha);

            $usuario->setSenha(password_hash($novaSenha, PASSWORD_DEFAULT));
            $usuario->setSenhaTemporaria(false);
            UsuarioDAO::atualizar($usuario);

            $_SESSION['usuario']['senhaTemporaria'] = false;
            $_SESSION['flash'] = ['tipo' => 'success', 'mensagem' => 'Senha alterada com sucesso!'];
            header('Location: ' . BASE_URL . '/dashboard');
            exit;

        } catch (Exception $ex) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'mensagem' => $ex->getMessage()];
            header('Location: ' . BASE_URL . '/alterar-senha');
            exit;
        }
    }
}