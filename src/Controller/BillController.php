<?php

namespace App\Controller;

use App\Repository\OrdonanceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BillController extends AbstractController
{
    #[Route('ordonance/{id}/bill', name: 'app_bill')]
    public function index($id, OrdonanceRepository $ordonanceRepository): Response
    {
        $ordonance = $ordonanceRepository->find($id);

        $pdfOptionns = new Options();

        $pdfOptionns->set('defaultFont','Arial');
        $dompdf = new Dompdf();
        $html = $this->renderView('bill/index.html.twig', [
         'ordonance' => $ordonance,
            
        ]);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $dompdf->stream("ordonance-".$ordonance->getId().'pdf',[
            'Attachment'=>false
        ]);
       

        return new Response('',200,[
            'Content-type'=>'application/pdf'
        ]);
        
    }
}
