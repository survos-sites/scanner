<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\UserBook;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use Genkgo\Favicon\Input;
use Scriptotek\GoogleBooks\GoogleBooks;
use Survos\Scraper\Service\ScraperService;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Genkgo\Favicon;
use Genkgo\Favicon\InputImageType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\String\u;

class AppController extends AbstractController
{
    public function __construct(
        private BookRepository $bookRepository,
        private AuthorRepository $authorRepository,
        private UserBookRepository $userBookRepository,
        private EntityManagerInterface $entityManager,
        private ScraperService $scraperService,
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
    public function searchISBN(string $id='9780140328721'   )
    {

        $books = new GoogleBooks(); // ['key' => 'YOUR_API_KEY_HERE']);
        $volume = $books->volumes->byIsbn($id   );
//        dd($volume);

        if (!$book = $this->bookRepository->findOneBy(['isbn' => $id])) {
            $book = (new Book($id));
            $this->entityManager->persist($book);
        }
        /** @var User $user */
        if ($user = $this->getUser()) {
            if (!$userBook = $this->userBookRepository->findOneBy([
                'user' => $user,
                'book' => $book
            ])) {
                $userBook = (new UserBook())
                    ->setUser($userBook)
                    ->setBook($book);
                $this->entityManager->persist($userBook);
            }
            $user->addUserBook($userBook);
        }

        // https://openlibrary.org/isbn/9780140328721
        $json = $this->scraperService->fetchData("https://openlibrary.org/isbn/{$id}.json");
        if (empty($json) || !array_key_exists('title', $json)) {
            $json['title'] = "Not found: " . $id;
            $book->setStatus(Book::STATUS_NOT_FOUND);
            // try google books?
            $volume = $books->volumes->byIsbn($id);
            if ($volume) {
                dd($volume, $id);
            }


        }
        $this->setAuthors($json, $book);
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

    private function setAuthors($data, Book $book)
    {
        foreach ($data['authors']??[] as $authorData) {
            $olId = u($authorData['key'])->after('/authors/')->toString();
            $json = $this->scraperService->fetchData("https://openlibrary.org/author/{$olId}.json");
            if (!$author = $this->authorRepository->findOneBy(['olId' => $olId])) {
                $author = (new Author())->setOlId($olId);
                $this->entityManager->persist($author);
            }
            $author->setInfo($json);
            $book->addAuthor($author);
        }

    }
}
