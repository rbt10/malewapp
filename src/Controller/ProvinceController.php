<?php

namespace App\Controller;

use App\Entity\SpecialiteProvince;
use App\Entity\Recette;
use App\Repository\SpecialiteProvinceRepository;
use App\Services\NavProvinces;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/province')]
class ProvinceController extends AbstractController
{
    private NavProvinces $navProvinces;


    public function __construct( NavProvinces $navProvinces)
    {
        $this->navProvinces = $navProvinces;
    }
    #[Route('/province/{slug}', name: 'app_province_index', methods: ['GET'])]
    public function index(SpecialiteProvince $province): Response
    {

        $recettes = $province->getRecettes();

        return $this->render('province/index.html.twig', [
            'recettes' => $recettes,
            'province' =>$province,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }


}
