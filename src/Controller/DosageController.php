<?php

namespace App\Controller;

use App\Entity\Dosage;
use App\Form\DosageType;
use App\Repository\DosageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dosage')]
class DosageController extends AbstractController
{
    #[Route('/', name: 'app_dosage_index', methods: ['GET'])]
    public function index(DosageRepository $dosageRepository): Response
    {
        return $this->render('dosage/index.html.twig', [
            'dosages' => $dosageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dosage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dosage = new Dosage();
        $form = $this->createForm(DosageType::class, $dosage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dosage);
            $entityManager->flush();

            return $this->redirectToRoute('app_dosage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dosage/new.html.twig', [
            'dosage' => $dosage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dosage_show', methods: ['GET'])]
    public function show(Dosage $dosage): Response
    {
        return $this->render('dosage/show.html.twig', [
            'dosage' => $dosage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dosage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dosage $dosage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DosageType::class, $dosage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dosage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dosage/edit.html.twig', [
            'dosage' => $dosage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dosage_delete', methods: ['POST'])]
    public function delete(Request $request, Dosage $dosage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dosage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dosage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dosage_index', [], Response::HTTP_SEE_OTHER);
    }
}
