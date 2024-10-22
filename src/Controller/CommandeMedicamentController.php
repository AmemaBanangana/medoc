<?php

namespace App\Controller;

use App\Entity\CommandeMedicament;
use App\Form\CommandeMedicamentType;
use App\Repository\CommandeMedicamentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande/medicament')]
class CommandeMedicamentController extends AbstractController
{
    #[Route('/', name: 'app_commande_medicament_index', methods: ['GET'])]
    public function index(CommandeMedicamentRepository $commandeMedicamentRepository): Response
    {
        return $this->render('commande_medicament/index.html.twig', [
            'commande_medicaments' => $commandeMedicamentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commandeMedicament = new CommandeMedicament();
        $form = $this->createForm(CommandeMedicamentType::class, $commandeMedicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commandeMedicament);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande_medicament/new.html.twig', [
            'commande_medicament' => $commandeMedicament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_medicament_show', methods: ['GET'])]
    public function show(CommandeMedicament $commandeMedicament): Response
    {
        return $this->render('commande_medicament/show.html.twig', [
            'commande_medicament' => $commandeMedicament,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CommandeMedicament $commandeMedicament, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeMedicamentType::class, $commandeMedicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_medicament_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande_medicament/edit.html.twig', [
            'commande_medicament' => $commandeMedicament,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_medicament_delete', methods: ['POST'])]
    public function delete(Request $request, CommandeMedicament $commandeMedicament, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandeMedicament->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commandeMedicament);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_medicament_index', [], Response::HTTP_SEE_OTHER);
    }
}
