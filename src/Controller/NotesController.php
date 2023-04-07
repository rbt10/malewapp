<?php

namespace App\Controller;

use App\Entity\Notes;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    private EntityManagerInterface $entityManager;


    /**
     * @param EntityManagerInterface $entityManager
     *
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    #[Route('/recette/{id}/note', name: 'recette_note', methods: ['POST'])]
    public function noterRecette(Request $request, RecetteRepository $recetteRepository, $id): JsonResponse
    {
        // Récupération de la recette à noter
        $recette = $recetteRepository->find($id);

        if (!$recette) {
            return new JsonResponse(['message' => 'Recette non trouvée'], Response::HTTP_NOT_FOUND);
        }

        // Récupération de la note et de l'utilisateur
        $note = $request->request->get('note');
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], Response::HTTP_UNAUTHORIZED);
        }

        // Vérification que l'utilisateur n'a pas déjà noté la recette
        $existingNote = $this->entityManager->getRepository(Notes::class)->findOneBy(['user' => $user, 'recette' => $recette]);

        if ($existingNote) {
            return new JsonResponse(['message' => 'Vous avez déjà noté cette recette'], Response::HTTP_BAD_REQUEST);
        }

        // Création de la note
        $noteObj = new Notes();
        $noteObj->setNote($note);
        $noteObj->setUser($user);
        $noteObj->setRecette($recette);

        // Enregistrement de la note
        $this->entityManager->persist($noteObj);
        $this->entityManager->flush();

        // Calcul de la nouvelle moyenne des notes pour la recette
        $moyenneNotes = $recetteRepository->calculerMoyenneNotes($recette);

        // Retour de la réponse avec la moyenne des notes
        return new JsonResponse(['moyenne' => $moyenneNotes]);
    }

}
