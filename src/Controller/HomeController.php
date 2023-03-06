<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Recette;
use App\Form\HomeType;
use App\Form\SearchType;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
     private NavProvinces $navProvinces;

    /**
     * @param EntityManagerInterface $entityManager
     * @param NavProvinces $navProvinces
     */
    public function __construct(EntityManagerInterface $entityManager, NavProvinces $navProvinces)
    {
        $this->entityManager = $entityManager;
        $this->navProvinces = $navProvinces;
    }


    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $recettes =  $this->entityManager->getRepository(Recette::class)->findByIsBest(1);

        $search = new Search();
        $form = $this->createForm(HomeType::class,$search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $recettes = $this->entityManager->getRepository(Recette::class)->findWithSearch($search);
            return $this->render('recette/index.html.twig',[
                'recettes'=>$recettes,
                'form'=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]);
        }

        return $this->render('home/index.html.twig',[
                "recettes" => $recettes,
                "form"=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
        ]
       );
    }

}
