<?php

namespace dao;

use model\Produto;
use utils\Conexao;

class ProdutoDAO extends GenericDAO
{
    protected static $modelClass = Produto::class;

    public static function buscarPortNome($nome)
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['nome' => $nome]);

    }

    public static function buscarPorSku($sku){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['sku' => $sku]);
    }

    public static function buscarPorMarca($marca){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['marca' => $marca]);
    }

    public static function listarEstoqueBaixo(){
        $em = Conexao::getEntityManager();

        $dql = "SELECT p FROM " . self::$modelClass . " p WHERE p.quantidadeEstoque < p.quantidadeMinima";

        return $em->createQuery($dql)->getResult();
    }

}