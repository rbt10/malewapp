<?php

namespace App\Controller\Recette\Videos;

use App\Entity\Commentaire;
use App\Entity\Recette;
use App\Entity\ThumbnailImage;
use App\Form\CommentaireType;
use App\Form\RecetteType;
use App\Form\VideoType;
use App\Repository\RecetteRepository;
use App\Services\NavProvinces;
use App\Services\Pictures;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecetteVideosController extends AbstractController
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

    /**
     * @throws \Exception
     */

    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
    #[Route('recette/Modifier-recetteVideo/{id}', name: 'app_modifier', methods: ['GET', 'POST'])]
    public function modifierVideo(Request $request,SluggerInterface $slugger, RecetteRepository $repository, Recette $recette): Response{

        $form = $this->createForm(RecetteType::class,$recette);

        $form->handleRequest($request);

        // Est-ce que le formulaire a été soumis
        if ($form->isSubmitted() && $form->isValid()){

            $recette = $form->getData();
            $recette->setUpdatedAt(new \DateTimeImmutable());
            $recette->setUser($this->getUser());

            // oui ajouter l'objet recette dans la base des données
            // ... persist the $recette variable or any other work

            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifiée avec succès'
            );

            //Rediriger vers mes recettes; affichage message succès
            return $this->redirectToRoute('app_mes_recettesVideos');

        }
        else{
            //Sinon
            // on affiche notre formulaire

            return $this->render('recette/admin/modifierRecetteVideo.html.twig',[
                'form'=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]);
        }

    }


    /**
     * Liste des recettes de chaque utilisateur
     * @param RecetteRepository $repository
     * @return Response
     */
    #[Route('recette/mes-recettesVideos', name: 'app_mes_recettesVideos', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function mesRecettesVideos(RecetteRepository $repository): Response {
        $recettes = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('recette/admin/mesRecettesVideos.html.twig', [
            'recettes' => $recettes,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }


    #[Route('/recette/video/{slug}',name: 'app_showVideo', methods: ['GET', 'POST'])]
    public function showVideo($slug, Request $request): Response
    {
        $recette = $this->entityManager->getRepository(Recette::class)->findOneBySlug($slug);
        if (!$recette){
            $this->redirectToRoute('app_recette');
        }

        // commentaire
        //on crée le commentaire

        $commentaire = new Commentaire();
        $commentaire->setAuteur($this->getUser());

        // on génère le formulaire

        $commentForm = $this->createForm(CommentaireType::class,$commentaire);
        $commentForm->handleRequest($request);

        // On traite le formulaire

        if ($commentForm->isSubmitted() && $commentForm->isValid()){

            $commentaire->setCreatedAt(new \DateTimeImmutable());
            $commentaire->setRecette($recette);

            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();

            $this->addFlash('success', 'votre commentaire a bien été envoyé');

            return $this->redirectToRoute('app_showVideo', ['slug'=> $recette->getSlug()]);
        }

        return $this->render('recette/show.html.twig',[
            'recette'=>$recette,
            'commentForm' => $commentForm->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

}
