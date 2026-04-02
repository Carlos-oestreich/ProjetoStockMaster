<?php

namespace CarlosELarissa\Stockmaster\dao;


use CarlosELarissa\Stockmaster\model\Usuario;
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

}