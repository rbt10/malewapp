<?php

namespace App\Controller\Recette;

use App\Classe\Search;
use App\Entity\Commentaire;
use App\Entity\Favoris;
use App\Entity\Recette;
use App\Form\AjoutRecetteType;
use App\Form\CommentaireType;
use App\Form\SearchType;
use App\Form\VideoType;
use App\Repository\CategorieRecetteRepository;
use App\Repository\FavorisRepository;
use App\Repository\RecetteRepository;
use App\Repository\SpecialiteProvinceRepository;
use App\Services\NavProvinces;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecetteController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private $navProvinces;

    /**
     * @param $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, NavProvinces $navProvinces)
    {
        $this->entityManager = $entityManager;
        $this->navProvinces = $navProvinces;
    }



    /**
     * Affichage de la liste des recettes
     * @param Request $request
     * @return Response
     */

    #[Route('/recette', name: 'app_recette')]
    public function index(CategorieRecetteRepository $categorie,PaginatorInterface $paginator, Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $recettes = $this->entityManager->getRepository(Recette::class)->findPublished( $request->query->getInt('page',1));;


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $recettes = $this->entityManager->getRepository(Recette::class)->findWithSearch($search);
        }

        return $this->render('recette/index.html.twig',[
            'recettes'=>$recettes,
            'form'=>$form->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    /**
     * Formulaire d'ajout d'une recette( seulles utilisateurs connecté peuvent le faire)
     * @param Request $request
     * @return Response
     */
    #[Route('recette/Ajouter-recette', name: 'app_Ajouter_recette', methods: ['GET', 'recette'])]
    #[IsGranted('ROLE_USER')]
    public function ajouter(Request $request, SluggerInterface $slugger): Response
    {
        $recette = new Recette();

        $form = $this->createForm(AjoutRecetteType::class,$recette);
        $videoForm = $this->createForm(VideoType::class,$recette);

        $form->handleRequest($request);
        $videoForm->handleRequest($request);
        // Est-ce que le formulaire a été soumis

        if ($form->isSubmitted() && $form->isValid() OR $videoForm->isSubmitted() && $videoForm->isValid()){

            $recette->setIsBest(false);
            $recette->setUpdatedAt(new \DateTimeImmutable());

            //liaison utilisateur avec la recette
            $recette = $form->getData();
            $recette->setUser($this->getUser());

            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été ajoutée avec succès'
            );

            //Rediriger vers mes recettes; affichage message succès
            return $this->redirectToRoute('app_mes_recettes');

        }
        else{
            //Sinon
                // on affiche notre formulaire

            return $this->render('recette/ajouterRecette.html.twig',[
                'form'=>$form->createView(),
                'videoForm'=>$videoForm->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]);
        }

    }

    #[Route('recette/Ajouter-Video', name: 'app_Ajouter_video', methods: ['GET', 'recette'])]
    #[IsGranted('ROLE_ADMIN')]
    public function ajouterVideo(Request $request, SluggerInterface $slugger): Response
    {
        $recette = new Recette();

        $videoForm = $this->createForm(VideoType::class,$recette);

        $videoForm->handleRequest($request);
        // Est-ce que le formulaire a été soumis

        if ($videoForm->isSubmitted() && $videoForm->isValid()){

            $recette->setIsBest(false);
            $recette->setUpdatedAt(new \DateTimeImmutable());

            //liaison utilisateur avec la recette
            $recette = $videoForm->getData();
            $recette->setUser($this->getUser());

            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été ajoutée avec succès'
            );

            //Rediriger vers mes recettes; affichage message succès
            return $this->redirectToRoute('app_mes_recettes');

        }
        else{
            //Sinon
            // on affiche notre formulaire

            return $this->render('recette/videoRecette.html.twig',[
                'videoForm'=>$videoForm->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]);
        }

    }

    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
    #[Route('recette/Modifier-recette/{id}', name: 'app_modifier', methods: ['GET', 'recette'])]

    public function modifier(Request $request,SluggerInterface $slugger, RecetteRepository $repository, Recette $recette): Response{

        $form = $this->createForm(AjoutRecetteType::class,$recette);

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
            return $this->redirectToRoute('app_mes_recettes');

        }
        else{
            //Sinon
            // on affiche notre formulaire

            return $this->render('recette/modifierRecette.html.twig',[
                'form'=>$form->createView(),
                'provinces' => $this->navProvinces->provinces()
            ]);
        }

    }

    /**
     * @param Recette $recette
     * @return Response
     */
    #[Route('recette/suppression/{id}', name: 'app_supprimer', methods: ['GET'])]
    public function supprimer(Recette $recette): Response
    {

        $this->entityManager->remove($recette);
        $this->entityManager->flush();

        $this->addFlash(
            'success',
            'Votre recette a été supprimé avec succès'
        );
        return  $this->redirectToRoute('app_mes_recettes');
    }

    /**
     * Liste des recettes de chaque utilisateur
     * @param RecetteRepository $repository
     * @return Response
     */
    #[Route('/mes-recettes', name: 'app_mes_recettes', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function mesRecettes(RecetteRepository $repository): Response {
        $recettes = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('recette/mesRecettes.html.twig', [
            'recettes' => $recettes,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }


    /**
     * Permet d'afficher une recette
     * @Route("/recette/{slug}", name="recette")
     */
    public function show($slug, Request $request): Response
    {
        $recette = $this->entityManager->getRepository(Recette::class)->findOneBySlug($slug);
        $recettes =  $this->entityManager->getRepository(Recette::class)->findByIsBest(1);
        if (!$recette){
            return $this->redirectToRoute('app_recette');
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

            return $this->redirectToRoute('recette', ['slug'=> $recette->getSlug()]);
        }

        return $this->render('recette/show.html.twig',[
            'recette'=>$recette,
            'recettes' => $recettes,
            'commentForm' => $commentForm->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }


    /**
     * @param Recette $recette
     * @param FavorisRepository $favorisRepository
     * @return Response
     */
    #[Route('/recette/{id}/like', name: 'app_like')]
    #[IsGranted('ROLE_USER')]
    public function like(Recette $recette): Response
    {
        $user = $this->getUser();

        if($recette->isLikedByUser($user)){
            $recette->removeFavorite($user);
            $this->entityManager->flush();

            return $this->json([
                'message'=>'like supprimé',
                'likes' => $recette->howManyLikes()
            ]);

        }

        $recette->addFavorite($user);
        $this->entityManager->flush();
        return $this->json([ 'message'=>'like ajouté' ,'likes' => $recette->howManyLikes()]);

    
    }
}
