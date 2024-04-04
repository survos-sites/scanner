<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Scriptotek\GoogleBooks\GoogleBooks;
use Scriptotek\GoogleBooks\Volume;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private PropertyAccessorInterface $accessor,
        private HttpClientInterface $client
    )
    {
    }

    public function exportBooks(?User $user=null)
    {
        $books = $user ? $user->getBooks() : $this->bookRepository->findAll();
        $csv = $this->serializer->serialize($books, 'csv', ['groups' => ['book.export']]);
//        file_put_contents('books.csv', $csv);
        return $csv;
    }

    public function lookup($isbn): Book
    {
//        $books = new GoogleBooks(); // ['key' => 'YOUR_API_KEY_HERE']);

        if (!$book = $this->bookRepository->findOneBy(['isbn' => $isbn])) {
            $book = (new Book($isbn));
            $this->entityManager->persist($book);
        }
        // if no data, make the API call to the Google Books API to get the info
        $base = 'https://www.googleapis.com/books/v1/volumes?projection=FULL&';
        if (!$book->getInfo()) {
            // @todo: api key
            $response = $this->client->request('GET', $base . 'q=isbn:' . $isbn);
            $data = $response->toArray();
            if (!$data['totalItems']) {
                // hack, try without ISBN, e.g. 9781439102817
                $response = $this->client->request('GET', $base . 'q=' . $isbn);
                $data = $response->toArray();
                $book->setStatus(Book::STATUS_MAYBE);
            } else {
                $book->setStatus(Book::STATUS_OK);

            }
            if ($data['totalItems']) {
                $volume = $data['items'][0]['volumeInfo']; // ['title'];  // "Harry Potter and the Philosopher's Stone"
//            $volume = $books->volumes->byIsbn($isbn);
                $hack = (array)$volume;
//                dump($hack);
//                $info =  (array) $hack["\x00*\x00data"];
                $book->setInfo($hack);
//            dd($hack, array_keys($hack),);
//            $data = $this->accessor->getValue($volume, 'data');
//            dd($data, $volume);
            } else {
                $book->setInfo(null);
                $book->setStatus(Book::STATUS_NOT_FOUND);
            }
        }
        $info = $book->getInfo();
//        $book->setInfo($volume->getData);
        if ($info && $book->getStatus() <> Book::STATUS_NOT_FOUND) {
            $info = (object)$info;
            $volumeInfo = (object)$info;
            /** @var Volume $volume */
//            if (!$volume->getCover()) {
//                dd($volume);
//            }
            dump($volumeInfo);
            $book
                ->setTitle($volumeInfo->title)
                ->setDescription($volumeInfo->description??null)
            ;
            // lookup with openlibrary?
        }
        $this->entityManager->flush();

        return $book;

    }
}
