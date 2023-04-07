<?php

namespace App\Controller\Recette;

use App\Classe\Search;
use App\Entity\Commentaire;
use App\Entity\Recette;
use App\Entity\ThumbnailImage;
use App\Form\CommentaireType;
use App\Form\RecetteType;
use App\Form\SearchType;
use App\Form\VideoType;
use App\Repository\CategorieRecetteRepository;
use App\Repository\RecetteRepository;
use App\Services\NavProvinces;
use App\Services\Pictures;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecetteController extends AbstractController
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
     * Affichage de la liste des recettes
     * @param CategorieRecetteRepository $categorie
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/recette', name: 'app_recette')]
    public function index(CategorieRecetteRepository $categorie,PaginatorInterface $paginator, Request $request): Response
    {
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $data = $this->entityManager->getRepository(Recette::class)->findAll();
        $recettes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),12);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $recettes = $this->entityManager->getRepository(Recette::class)->findWithSearch($search);
        }

        return $this->render('recette/index.html.twig',[
            'recettes'=>$recettes,
            'form'=>$form->createView(),
            'provinces' => $this->navProvinces->provinces(),

        ]);
    }
    /**
     * Ajouter une recette avec une image
     *
    */

   #[Security("is_granted('ROLE_USER')")]
   #[Route('recette/Ajout', name : 'app_ajouter', methods: ['GET', 'POST'])]
    public function Ajout( Request $request, SluggerInterface $slugger) : Response{

        $recette =  new Recette();

        $formRecette = $this->createForm(RecetteType::class, $recette);

        //on traite la requete du formulaire
        $formRecette->handleRequest($request);

        //on vérifie si le formulaire est valide et soumis
        if ($formRecette->isSubmitted() && $formRecette->isValid()){

            $slug = $slugger->slug($recette->getName());
            $recette->setName($slug);

            $recette->setUser($this->getUser());

            // stockage dans la bdd
            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été ajoutée avec succès'
            );
            //Rediriger vers mes recettes; affichage message succès
            return $this->redirectToRoute('app_mes_recettes');
        }

        return $this->render('recette/ajouterRecette.html.twig', [
            'form'=>$formRecette->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);

    }

    /**
     * Ajouter une recette avec une vidéo (réservé aux admins)
     * @throws Exception
     */

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('recette/AjoutVideo', name : 'app_video', methods: ['GET', 'POST'])]
    public function AjoutVideo( Request $request,
                                SluggerInterface $slugger,
                                Pictures $pictures) : Response{

        $recette =  new Recette();

        $form = $this->createForm(VideoType::class, $recette);

        //on traite la requete du formulaire
        $form->handleRequest($request);

        //on vérifie si le formulaire est valide et soumis
        if ($form->isSubmitted() && $form->isValid()){

            // on recupère les images
            $images = $form->get('thumbnailImages')->getData();
            foreach($images as $image){
                // On définit le dossier de destination
                $folder = '';

                // On appelle le service d'ajout
                $fichier = $pictures->add($image, $folder, 300, 300);

                $img = new ThumbnailImage();
                $img->setNom($fichier);
                $recette->addThumbnailImage($img);
            }
            //  on génère le slug
            $slug = $slugger->slug($recette->getName());
            $recette->setName($slug);
            $recette->setUser($this->getUser());

            // stockage dans la bdd
            $this->entityManager->persist($recette);
            $this->entityManager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été ajoutée avec succès'
            );
            //Rediriger vers mes recettes; affichage message succès
            return $this->redirectToRoute('app_mes_recettes');
        }

        return $this->render('recette/videoRecette.html.twig', [
            'form'=>$form->createView(),
            'recettes'=>$recette,
            'provinces' => $this->navProvinces->provinces()
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === recette.getUser()")]
    #[Route('recette/Modifier-recette/{id}', name: 'app_modifier', methods: ['GET', 'POST'])]
    public function modifier(Request $request,SluggerInterface $slugger, RecetteRepository $repository, Recette $recette): Response{

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


    #[Route('/recette/{slug}',name: 'app_show', methods: ['GET', 'POST'])]
    public function show($slug, Request $request): Response
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

            return $this->redirectToRoute('app_show', ['slug'=> $recette->getSlug()]);
        }

        return $this->render('recette/show.html.twig',[
            'recette'=>$recette,
            'commentForm' => $commentForm->createView(),
            'provinces' => $this->navProvinces->provinces()
        ]);
    }


    /**
     * @param Recette $recette
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
