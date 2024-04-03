<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Scriptotek\GoogleBooks\GoogleBooks;
use Scriptotek\GoogleBooks\Volume;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private PropertyAccessorInterface $accessor,
    )
    {
    }

    public function exportBooks()
    {
        $books = $this->bookRepository->findAll();
        $csv = $this->serializer->serialize($books, 'csv', ['groups' => ['book.export']]);
        file_put_contents('books.csv', $csv);
        dd($csv);
        return $csv;
    }

    public function lookup($isbn): Book
    {
        $books = new GoogleBooks(); // ['key' => 'YOUR_API_KEY_HERE']);

        if (!$book = $this->bookRepository->findOneBy(['isbn' => $isbn])) {
            $book = (new Book($isbn));
            $this->entityManager->persist($book);
        }
        if (!$book->getInfo()) {
            $volume = $books->volumes->byIsbn($isbn);
            if ($volume) {
                $hack = (array)$volume;
                $info =  (array) $hack["\x00*\x00data"];
                $book->setInfo($info);
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
        $info = (object)$info;
        if ($info) {
            if (!property_exists($info, 'volumeInfo')) {
                return $book;
                dd($info, $volume, $isbn);
            }
            $volumeInfo = (object)$info->volumeInfo;
            /** @var Volume $volume */
//            if (!$volume->getCover()) {
//                dd($volume);
//            }
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
