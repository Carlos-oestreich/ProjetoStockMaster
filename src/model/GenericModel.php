<?php

namespace CarlosELarissa\Stockmaster\model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class GenericModel{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}