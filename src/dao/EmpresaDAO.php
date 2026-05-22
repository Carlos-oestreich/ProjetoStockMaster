<?php

namespace dao;

use model\Empresa;
use utils\Conexao;

class EmpresaDAO extends GenericDAO
{
    protected static $modelClass = Empresa::class;

    public static function buscarPorId($id)
    {
        $em = Conexao::getEntityManager();
        return $em->getRepository(self::$modelClass)->find($id);
    }
}