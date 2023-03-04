<?php

namespace App\Controller\Utilisateurs;

use App\Form\ContactType;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private NavProvinces $navProvinces;


    public function __construct( NavProvinces $navProvinces)
    {

        $this->navProvinces = $navProvinces;
    }
    #[Route('/nous-contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $notification = null;
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $notification = "Merci de nous avoir contacté, notre équipe va vous contacter dans les meilleurs delais";
        }

        return $this->render('contact/index.html.twig',[
                'form'=>$form->createView(),
                'notification'=>$notification,
                'provinces' => $this->navProvinces->provinces()
        ]
        );
    }
}
