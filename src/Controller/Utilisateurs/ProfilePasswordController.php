<?php

namespace App\Controller\Utilisateurs;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfilePasswordController extends AbstractController
{
    private NavProvinces $navProvinces;


    public function __construct( NavProvinces $navProvinces)
    {

        $this->navProvinces = $navProvinces;
    }

    #[Security("is_granted('ROLE_USER')")]
    #[Route('/profile/modifier-mon-mot-de-passe', name: 'app_profile_password')]
    public function editPassword( Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface  $entityManager): Response
    {

        $user = $this->getUser();
        $notification = null;

        $form = $this->createForm(ChangePasswordType::class, $user);

        //traiter le formulaire

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $old_password = $form->get('old_password')->getData();

            // On encode le le nouveau mot de passe
            if($userPasswordHasher->isPasswordValid($user, $old_password)){

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('new_password')->getData()
                    ));


                $entityManager->persist($user);
                $entityManager->flush();

                $notification = 'Votre mot de passe a bien été mis à jour';
            }else{
                $notification ="votre mot de passe n'est pas à jour";
            }

        }

        return $this->render('profile/password.html.twig',[
            'form'=>$form->createView(),
            'notification'=>$notification,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }
}
