<?php

namespace App\Controller;

use App\Entity\Forme;
use App\Form\FormeType;
use App\Repository\FormeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/forme')]
class FormeController extends AbstractController
{
    #[Route('/', name: 'app_forme_index', methods: ['GET'])]
    public function index(FormeRepository $formeRepository): Response
    {
        return $this->render('forme/index.html.twig', [
            'formes' => $formeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_forme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forme = new Forme();
        $form = $this->createForm(FormeType::class, $forme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($forme);
            $entityManager->flush();

            return $this->redirectToRoute('app_forme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forme/new.html.twig', [
            'forme' => $forme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forme_show', methods: ['GET'])]
    public function show(Forme $forme): Response
    {
        return $this->render('forme/show.html.twig', [
            'forme' => $forme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_forme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forme $forme, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormeType::class, $forme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forme/edit.html.twig', [
            'forme' => $forme,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forme_delete', methods: ['POST'])]
    public function delete(Request $request, Forme $forme, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forme->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($forme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forme_index', [], Response::HTTP_SEE_OTHER);
    }
}
