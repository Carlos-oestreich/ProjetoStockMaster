<?php

namespace CarlosELarissa\Stockmaster\dao;

use utils\Conexao;
use Exception;
use CarlosELarissa\Stockmaster\model\GenericModel;



class GenericDAO {

    protected static $modelClass;

    public static function salvar(GenericModel $model) {

        try{
            $em = Conexao::getEntityManager();
            $em->beginTransaction();
            $em->persist($model);
            $em->flush();
            $em->commit();
            return $model;

        } catch (Exception $ex){
            if ($em->getConnection()->isTransactionActive()) {
                $em->rollback();
            }
            throw new Exception("Falha ao atualizar os dados." . $ex->getMessage());
        }

    }

    public static function listar() {
        try {
            $em = Conexao::getEntityManager();
            $repository = $em->getRepository(static::$modelClass);
            return $repository->findAll();
        } catch (Exception $ex) {
            throw new Exception("Falha ao listar os dados." . $ex->getMessage());
        }
    }

    public static function buscarPorId($id) {
        try {
            $em = Conexao::getEntityManager();
            return $em->find(static::$modelClass, $id);
        }catch (Exception $ex){
            throw new Exception("Falha ao buscar os dados por id." . $ex->getMessage());
        }
    }

    public static function atualizar(GenericModel $model)
    {
        try {
            $em = Conexao::getEntityManager();
            $em->beginTransaction();
            $em->flush();
            $em->commit();
            return $model;
        } catch (Exception $ex) {
            if ($em->getConnection()->isTransactionActive()) {
                $em->rollback();
            }
            throw new Exception("Falha ao atualizar os dados." . $ex->getMessage());
        }
    }

    public static function deletar($id){
        try{
            $em = Conexao::getEntityManager();
            $registro = $em->find(static::$modelClass, $id);

            if($registro === null){
                return false;
            }

            $em->beginTransaction();
            $em->remove($registro);
            $em->flush();
            $em->commit();

            return true;
        } catch (Exception $ex){
            if ($em->getConnection()->isTransactionActive()) {
                $em->rollback();
            }
            throw new Exception("Falha ao deletar os dados." . $ex->getMessage());
        }
    }
}