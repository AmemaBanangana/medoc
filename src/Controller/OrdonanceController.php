<?php

namespace App\Controller;

use App\Entity\Ordonance;
use App\Form\OrdonanceType;
use App\Repository\OrdonanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/medecin/ordonance')]
class OrdonanceController extends AbstractController
{
    #[Route('/', name: 'app_ordonance_index', methods: ['GET'])]
    public function index(OrdonanceRepository $ordonanceRepository): Response
    {
        $forme = $ordonanceRepository->findeByForme();
        return $this->render('ordonance/index.html.twig', [
            'ordonances' => $ordonanceRepository->ordonance(),
            'forme' => $forme,
        ]);
    }

    #[Route('/new', name: 'app_ordonance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ordonance = new Ordonance();
        $form = $this->createForm(OrdonanceType::class, $ordonance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($ordonance);
            $entityManager->flush();

            return $this->redirectToRoute('app_ordonance_index', ['id' => $ordonance->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ordonance/new.html.twig', [
            'ordonance' => $ordonance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ordonance_show', methods: ['GET'])]
    public function show(Ordonance $ordonance): Response
    {
        return $this->render('ordonance/show.html.twig', [
            'ordonance' => $ordonance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ordonance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ordonance $ordonance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrdonanceType::class, $ordonance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ordonance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ordonance/edit.html.twig', [
            'ordonance' => $ordonance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ordonance_delete', methods: ['POST'])]
    public function delete(Request $request, Ordonance $ordonance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordonance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ordonance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ordonance_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/ordonance/success/{id}', name: 'ordonnance_success')]
public function success(Ordonance $ordonnance): Response {
    return $this->render('ordonance/success.html.twig', [
        'ordonnance' => $ordonnance,
    ]);
}


}
