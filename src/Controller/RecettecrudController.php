<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recettecrud')]
class RecettecrudController extends AbstractController
{
    #[Route('/', name: 'app_recettecrud_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recettecrud/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recettecrud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RecetteRepository $recetteRepository): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->add($recette, true);

            return $this->redirectToRoute('app_recettecrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recettecrud/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recettecrud_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recettecrud/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recettecrud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteRepository->add($recette, true);

            return $this->redirectToRoute('app_recettecrud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('recettecrud/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recettecrud_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, RecetteRepository $recetteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $recetteRepository->remove($recette, true);
        }

        return $this->redirectToRoute('app_recettecrud_index', [], Response::HTTP_SEE_OTHER);
    }
}
