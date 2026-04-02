<?php

namespace CarlosELarissa\Stockmaster\dao;

use CarlosELarissa\Stockmaster\model\Categoria;
use utils\Conexao;

class CategoriaDAO extends GenericDAO
{
    protected static $modelClass = Categoria::class;

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