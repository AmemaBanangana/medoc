<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAllWithMedicaments();
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $commande->setStatut('Nouvelle'); // Statut initial
    
        $form = $this->createForm(CommandeType::class, $commande);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($commande->getCommandeMedicaments() as $commandeMedicament) {
                $commandeMedicament->setCommande($commande);
                $entityManager->persist($commandeMedicament);
            }
    
            $entityManager->persist($commande);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commande_index');
        }
    
        return $this->render('commande/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }

     
     #[Route("/admin/commandes", name:"admin_commandes")]
     
     public function adminCommandes(EntityManagerInterface $entityManager): Response
     {
         $commandes = $entityManager->getRepository(Commande::class)->findBy(['statut' => 'Nouvelle']);
 
         return $this->render('admin/commandes.html.twig', [
             'commandes' => $commandes,
         ]);
     }
 

     #[Route("/admin/commandes/valider/{id}", name:"valider_commande")]
  
    public function validerCommande($id, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('No commande found for id '.$id);
        }

        $commande->setStatut('Validée');
        $entityManager->flush();

        return $this->redirectToRoute('admin_commandes');
    }

    #[Route("/admin/commandes/refuser/{id}", name:"refuser_commande")]
   
    public function refuserCommande($id, EntityManagerInterface $entityManager): Response
    {
        $commande = $entityManager->getRepository(Commande::class)->find($id);

        if (!$commande) {
            throw $this->createNotFoundException('No commande found for id '.$id);
        }

        $commande->setStatut('Refusée');
        $entityManager->flush();

        return $this->redirectToRoute('admin_commandes');
    }

    
 #[Route("/commandes/livree", name:"commandes_livree")]

public function commandesLivree(EntityManagerInterface $entityManager): Response
{
    $commandes = $entityManager->getRepository(Commande::class)->findBy(['statut' => 'Validée']);

    return $this->render('commande/livree.html.twig', [
        'commandes' => $commandes,
    ]);
}

 #[Route("/commandes/non_livree", name:"commandes_non_livree")]

public function commandesNonLivree(EntityManagerInterface $entityManager): Response
{
    $commandes = $entityManager->getRepository(Commande::class)->findBy(['statut' => 'Refusée']);

    return $this->render('commande/non_livree.html.twig', [
        'commandes' => $commandes,
    ]);
}
}
