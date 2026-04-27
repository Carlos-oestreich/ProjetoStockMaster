<?php

namespace dao;

use model\MovimentacoesEstoque;
use utils\Conexao;

class MovimentacaoEstoqueDAO extends GenericDAO
{
    protected static $modelClass = MovimentacoesEstoque::class;

    public static function buscarPorUsuario($usuario){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['usuario' => $usuario]);
    }

    public static function listarPorProduto(int $produtoId): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(
            ['produto' => $produtoId],
            ['dataMovimentacao' => 'DESC']
        );
    }

    public static function listarPorTipo(string $tipo): array
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findBy(['tipo' => $tipo]);
    }

    public static function listarRecentes(int $limite = 10): array
    {
        $em  = Conexao::getEntityManager();
        $dql = "SELECT m FROM " . self::$modelClass . " m ORDER BY m.dataMovimentacao DESC";
        return $em->createQuery($dql)->setMaxResults($limite)->getResult();
    }

    public static function listarComSaldoAtualMenorQue($valor){
        $em = Conexao::getEntityManager();

        $dql = "SELECT m FROM " . self::$modelClass . " m WHERE m.saldoAtual < :valor";

        return $em->createQuery($dql)
            ->setParameter('valor', $valor)
            ->getResult();
    }

}