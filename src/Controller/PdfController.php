<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Ordonance;
use App\Repository\OrdonanceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    private $pdf;
    private $doctrine;

    public function __construct(Pdf $pdf, ManagerRegistry $doctrine)
    {
        $this->pdf = $pdf;
        $this->doctrine = $doctrine;
    }

    #[Route('/ordonnance/{id}/pdf/', name: 'pdf_ordonnance')]
    public function generateOrdonnancePdf($id, OrdonanceRepository $ordonanceRepository): Response
    {
        // Récupère l'ordonnance et ses détails depuis la base de données
        $ordonnance = $this->doctrine->getRepository(Ordonance::class)->find($id);

        // Vérifie si l'ordonnance existe
        if (!$ordonnance) {
            throw $this->createNotFoundException('Ordonnance non trouvée');
        }

        // Chemin de sauvegarde du PDF
        $filePath = 'C:/path/to/save/pdfs/ordonnance_' . $id . '.pdf';

        // Vérifie et supprime le fichier existant
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $html = $this->renderView('ordonance/pdf.html.twig', [
            'ordonnance' => $ordonnance,
            'medecin' => $ordonnance->getMedecin()->getNom(),
            'patient' => $ordonnance->getPatien()->getNom(),
            'medocs' => $ordonnance->getMedicament(),
        ]);

        $pdfContent = $this->pdf->getOutputFromHtml($html);

        // Sauvegarde du PDF
        file_put_contents($filePath, $pdfContent);

        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="ordonnance.pdf"'
            ]
        );
    }
}
