<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use Genkgo\Favicon\Input;
use Survos\Scraper\Service\ScraperService;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Genkgo\Favicon;
use Genkgo\Favicon\InputImageType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AppController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager
    )
    {

    }
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
        ]);
    }

    #[Route('/_list', name: 'app_list')]
    public function _list(): Response
    {
        return $this->render('app/_list.html.twig',
            ['books' => $this->bookRepository->findBy([], ['createdAt' => 'DESC'], 20)
        ]);
    }

    #[Route('/scan', name: 'app_scan')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function scan(): Response
    {
        return $this->render('app/scan.html.twig', [
        ]);
    }

    #[Route('/isbn/{id}', name: 'app_isbn')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function searchISBN(ScraperService $scraperService, string $id='9780140328721')
    {
        $user = $this->getUser();
        if (!$book = $this->bookRepository->findOneBy(['user' => $user, 'isbn' => $id])) {
            $book = (new Book($id, $user));
            $this->entityManager->persist($book);
        }

        // https://openlibrary.org/isbn/9780140328721
        $json = $scraperService->fetchData("https://openlibrary.org/isbn/{$id}.json");
        if (empty($json) || !array_key_exists('title', $json)) {
            $json['title'] = "Not found: " . $id;
        }
        $book->setTitle($json['title'])
            ->setInfo($json);
        $this->entityManager->flush();
        return new JsonResponse($book->getInfo());
    }
    #[Route('/qr', name: 'app_qr')]
    #[Template('app/qr.html.twig')]
    public function qr(
        ScraperService $scraperService): array
    {
        return [];
    }
}
