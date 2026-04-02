<?php

require_once __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use utils\Conexao;

ConsoleRunner::run(
    new SingleManagerProvider(Conexao::getEntityManager())
);

