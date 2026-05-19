<?php

namespace dao;

use model\Categoria;
use utils\Conexao;

class CategoriaDAO extends GenericDAO
{
    protected static $modelClass = Categoria::class;

    public static function listarPorEmpresa(int $empresaId): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['empresa' => $empresaId]);
    }

    public static function buscarPorIdEEmpresa(int $id, int $empresaId): ?Categoria
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy([
            'id' => $id,
            'empresa' => $empresaId,
        ]);
    }

    public static function buscarPorNome($nome)
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['nome' => $nome]);

    }

    public static function buscarPorSetor($setor){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['setor' => $setor]);
    }

    public static function listarAtivos(){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['ativo' => true]);
    }

}