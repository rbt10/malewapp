<?php

namespace App\Controller;

use App\Entity\SpecialiteProvince;
use App\Entity\Recette;
use App\Repository\SpecialiteProvinceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/province')]
class ProvinceController extends AbstractController
{
    #[Route('/province/recette', name: 'app_province_index', methods: ['GET'])]
    public function index(SpecialiteProvinceRepository $specialiteProvinceRepository): Response
    {

        return $this->render('province/index.html.twig', [
            'specialite_provinces' => $specialiteProvinceRepository->findAll(),
        ]);
    }


}
