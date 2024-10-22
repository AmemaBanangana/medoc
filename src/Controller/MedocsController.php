<?php

namespace App\Controller;

use App\Entity\Medocs;
use App\Form\MedocsType;
use App\Entity\AddHistory;
use App\Form\AddhistoryType;
use App\Repository\FormeRepository;
use App\Repository\MedocsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/medocs')]
class MedocsController extends AbstractController
{
    #[Route('/', name: 'app_medocs_index', methods: ['GET'])]
    public function index(MedocsRepository $medocsRepository): Response
    {
        return $this->render('medocs/index.html.twig', [
            'medocs' => $medocsRepository->findAll(),
            'nombre' => $medocsRepository->count(),
        ]);
    }

    #[Route('/new', name: 'app_medocs_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $medoc = new Medocs();
        $form = $this->createForm(MedocsType::class, $medoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData();
            if ($imageFile){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                $medoc->setPhoto($newFilename);
            }
            $entityManager->persist($medoc);
            $entityManager->flush();

            $addHistory = new AddHistory();
            $addHistory->setQte($medoc->getStock());
            $addHistory->setMedocs($medoc);
            $addHistory->setDateAt(new \DateTimeImmutable());
            
            $entityManager->persist($addHistory);
            $entityManager->flush();

            return $this->redirectToRoute('app_medocs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medocs/new.html.twig', [
            'medoc' => $medoc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medocs_show', methods: ['GET'])]
    public function show(Medocs $medoc): Response
    {
        return $this->render('medocs/show.html.twig', [
            'medoc' => $medoc,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_medocs_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medocs $medoc, EntityManagerInterface $entityManager,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(MedocsType::class, $medoc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('photo')->getData();
            if ($imageFile){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                $medoc->setPhoto($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_medocs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('medocs/edit.html.twig', [
            'medoc' => $medoc,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_medocs_delete', methods: ['POST'])]
    public function delete(Request $request, Medocs $medoc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $medoc->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($medoc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_medocs_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/medocs/expiring-soon', name: 'medocs_expiring_soon')]

    public function expiringSoon(MedocsRepository $medocsRepository): Response
    {
        $medocs = $medocsRepository->medocs();
        $medocss = $medocsRepository->findExpiringInOneMonth();
        $medoc = $medocsRepository->findExpiringInTwoMonths();

        return $this->render('medocs/expiring_soon.html.twig', [
            'medocs' => $medocs,
            'medocss' => $medocss,
            'medoc' => $medoc,
        ]);
    }

    #[Route('/medocs/medocs/{forme}', name: 'app_medicament')]
    public function indexee(MedocsRepository $medicamentRepository, $forme): Response
    {
        $medicaments = $medicamentRepository->findByForme($forme);

        return $this->render('medocs/list.html.twig', [
            'medicaments' => $medicaments,
            'forme' => $forme,

        ]);
    }
    #[Route('/add/stoks/{id}/', name: 'app_medocs_add_stock', methods: ['GET', 'POST'])]
    public function addStock($id, MedocsRepository $medocsRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $addstock = new AddHistory();
        $forme = $this->createForm(AddhistoryType::class,$addstock);
        $forme->handleRequest($request);
        $medocs = $medocsRepository->find($id);

        if ($forme->isSubmitted() && $forme->isValid()) {
            // recuperation de notre stock additionner le stokc ajouter
            if ($addstock->getQte() > 0) {
                $newQte =  $medocs->getStock() + $addstock->getQte();
                $medocs->setStock($newQte);

                $addstock->setDateAt(new \DateTimeImmutable());
                $addstock->setMedocs($medocs);

                // stoker historique
                $entityManager->persist($addstock);
                $entityManager->flush();
                $this->addFlash('success', 'stoke modifier');
                return $this->redirectToRoute('app_medocs_index');
            } else {
                $this->addFlash('danger', 'Desole, le stocke ne doit pas etre inferier a 0, veuillez ajouter');
                return $this->redirectToRoute('app_medocs_add_stock', ['id' => $medocs->getId()]);
            }
        }
        return $this->render(
            'medocs/addstock.html.twig',
            [
                'form' => $forme->createView(),
                'medocs' => $medocs,
            ]
        );
    }

     
     #[Route("/medocs/search", name:"search_medocs")]
     
    public function search(Request $request, MedocsRepository $medocsRepository): Response
    {
        $keyword = $request->query->get('q');
        $medocs = $medocsRepository->findByDescription($keyword);

        return $this->render('medocs/search.html.twig', [
            'medocs' => $medocs,
        ]);
    }
}
