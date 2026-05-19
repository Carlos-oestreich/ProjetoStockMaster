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

    public static function listarPorEmpresa(int $empresaId): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['empresa' => $empresaId]);
    }

    public static function buscarPorIdEEmpresa(int $id, int $empresaId): ?Produto
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy([
            'id' => $id,
            'empresa' => $empresaId,
        ]);
    }

    public static function listarEstoqueBaixoPorEmpresa(int $empresaId): array
    {
        $em = Conexao::getEntityManager();

        $dql = "SELECT p FROM " . self::$modelClass . " p"
            . " WHERE p.quantidadeEstoque < p.quantidadeMinima AND p.empresa = :empresa";

        return $em->createQuery($dql)
            ->setParameter('empresa', $empresaId)
            ->getResult();
    }

}