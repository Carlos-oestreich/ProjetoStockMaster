<?php

namespace CarlosELarissa\Stockmaster\dao;

use CarlosELarissa\Stockmaster\model\MovimentacoesEstoque;
use utils\Conexao;

class MovimentacaoEstoqueDAO extends GenericDAO
{
    protected static $modelClass = MovimentacoesEstoque::class;

    public static function buscarPorTipo($tipo){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['tipo' => $tipo]);
    }

    public static function buscarPorUsuario($usuario){
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->findOneBy(['usuario' => $usuario]);
    }

    public static function listarComSaldoAtualMenorQue($valor){
        $em = Conexao::getEntityManager();

        $dql = "SELECT m FROM " . self::$modelClass . " m WHERE m.saldoAtual < :valor";

        return $em->createQuery($dql)
            ->setParameter('valor', $valor)
            ->getResult();
    }

}