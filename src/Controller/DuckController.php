<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\DucksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/duck')]
class DuckController extends AbstractController
{
    #[Route('/', name: 'app_duck_index', methods: ['GET'])]
    public function index(DucksRepository $ducksRepository): Response
    {
        return $this->render('duck/index.html.twig', [
            'ducks' => $ducksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_duck_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DucksRepository $ducksRepository): Response
    {
        $duck = new Duck();
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ducksRepository->save($duck, true);

            return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('duck/new.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_duck_show', methods: ['GET'])]
    public function show(Duck $duck): Response
    {
        return $this->render('duck/show.html.twig', [
            'duck' => $duck,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_duck_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Duck $duck, DucksRepository $ducksRepository): Response
    {
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ducksRepository->save($duck, true);

            return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('duck/edit.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_duck_delete', methods: ['POST'])]
    public function delete(Request $request, Duck $duck, DucksRepository $ducksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->request->get('_token'))) {
            $ducksRepository->remove($duck, true);
        }

        return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
    }
}
