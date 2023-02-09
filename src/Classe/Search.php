<?php


namespace App\Classe;


use App\Entity\Categorie;
use App\Entity\SpecialiteProvince;

class Search
{
    /**
     * @var string
     */
    public  $string ='';


    /**
     * @var $categories array
     */
    public $categories = [];


    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->string;
    }
}