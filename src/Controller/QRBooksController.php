<?php

namespace App\Controller;

use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QRBooksController extends AbstractController
{
    #[Route('/qr-books', name: 'app_qr_books')]
    public function index(

    ): Response
    {
        $path = __DIR__ . '/../../libros.f1215.csv';
        $csv = Reader::createFromPath($path, 'r');
        $records = $csv->getRecords(); // an Iterator object containing arrays
        foreach ($records as $row) {
//            dd($row);
        }
        return $this->render('qr_books/index.html.twig', [
            'records' => $records
        ]);
    }
}
