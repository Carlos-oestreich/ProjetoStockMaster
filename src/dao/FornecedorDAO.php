<?php

namespace dao;



use model\Fornecedor;
use utils\Conexao;

class FornecedorDAO extends GenericDAO
{
    protected static $modelClass = Fornecedor::class;

    public static function listarPorEmpresa(int $empresaId): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['empresa' => $empresaId]);
    }

    public static function buscarPorIdEEmpresa(int $id, int $empresaId): ?Fornecedor
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy([
            'id' => $id,
            'empresa' => $empresaId,
        ]);
    }

    public static function buscarPorNome($nome){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['nome' => $nome]);
    }

    public static function buscarPorCnpj($cnpj){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['cnpj' => $cnpj]);
    }

    public static function listarAtivos(){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['ativo' => true]);
    }

}