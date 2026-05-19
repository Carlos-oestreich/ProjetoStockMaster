<?php

namespace dao;


use model\Usuario;
use utils\Conexao;

class UsuarioDAO extends GenericDAO
{
    protected static $modelClass = Usuario::class;

    public static function buscarPorNome($nome){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['nome' => $nome]);
    }

    public static function buscarPorEmail($email){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['email' => $email]);
    }

    public static function buscarPorPerfil($perfil){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['perfil' => $perfil]);
    }

    public static function buscarPorMatricula(string $matricula): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['matricula' => $matricula]);
    }

    public static function buscarPorEmailEEmpresa(string $email, int $empresaId): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy([
            'email'   => $email,
            'empresa' => $empresaId,
        ]);
    }

    public static function buscarPorCpf(string $cpf): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['cpf' => $cpf]);
    }

    public static function listarPorEmpresa(int $empresaId): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['empresa' => $empresaId]);
    }

    public static function buscarPorIdEEmpresa(int $id, int $empresaId): ?Usuario
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy([
            'id' => $id,
            'empresa' => $empresaId,
        ]);
    }

    public static function listarPorEmpresaEPerfil(int $empresaId, string $perfil): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy([
            'empresa' => $empresaId,
            'perfil'  => $perfil,
        ]);
    }

    public static function existeDonoPorEmpresa(int $empresaId): bool
    {
        $donos = self::listarPorEmpresaEPerfil($empresaId, 'DONO');
        return !empty($donos);
    }

    public static function buscarDonoDaEmpresa(int $empresaId): ?Usuario
    {
        $donos = self::listarPorEmpresaEPerfil($empresaId, 'DONO');
        return $donos[0] ?? null;
    }

    public static function existeDono(): bool
    {
        $em = Conexao::getEntityManager();
        $donos = $em->getRepository(self::$modelClass)->findBy(['perfil' => 'DONO']);
        return !empty($donos);
    }

}