<?php

namespace App\Controller\Utilisateurs;

use App\Entity\User;
use App\Form\UpdateProfileType;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private NavProvinces $navProvinces;


    public function __construct( NavProvinces $navProvinces)
    {

        $this->navProvinces = $navProvinces;
    }
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig',[
            'provinces' => $this->navProvinces->provinces()
        ]);
    }


    /**
     * Ce controller permet de faire la modification des utilisateurs
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('profile/modifier_profile/{id}', name: 'app_profile_modifier')]
    public function update_profile(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        if ($this->getUser() !== $user){
            return $this->redirectToRoute('app_mes_recettes');
        }

        $form = $this->createForm(UpdateProfileType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success','les informations de votre compte ont été modifié' );
            return  $this->redirectToRoute('app_profile');

        }

        return $this->render('profile/update_profile.html.twig',[
            'form'=>$form->createView(),
            'provinces' => $this->navProvinces->provinces()

        ]);
    }
}
