<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Form\RegistrationFormType;
use App\Repository\DucksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


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
    public function new(Request                     $request,
                        EntityManagerInterface      $entityManager,
                        UserPasswordHasherInterface $duckPasswordHashes):
    Response
    {
        $duck = new Duck();
        $form = $this->createForm(RegistrationFormType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $duck->setPassword(
                $duckPasswordHashes->hashPassword(
                    $duck,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($duck);
            $entityManager->flush();

            return $this->redirectToRoute('app_duck_index');
        }

        return $this->render('duck/new.html.twig', [
            'form' => $form->createView(),
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
    public function edit(Request $request, Duck $duck, DucksRepository $ducksRepository, $duckPasswordHashes, $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $duck->setPassword(
                $duckPasswordHashes->hashPassword(
                    $duck,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($duck);
            $entityManager->flush();

            return $this->redirectToRoute('app_duck_index');
        }

        return $this->render('duck/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_duck_delete', methods: ['POST'])]
    public function delete(Request $request, Duck $duck, DucksRepository $ducksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $duck->getId(), $request->request->get('_token'))) {
            $ducksRepository->remove($duck, true);
        }

        return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
    }
}
