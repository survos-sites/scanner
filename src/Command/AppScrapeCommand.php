<?php

namespace App\Command;

use App\Controller\AppController;
use App\Controller\BookController;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Zenstruck\Console\ConfigureWithAttributes;
use Zenstruck\Console\InvokableServiceCommand;
use Zenstruck\Console\IO;
use Zenstruck\Console\RunsCommands;
use Zenstruck\Console\RunsProcesses;

#[AsCommand('app:scrape', 'Add book via Open Library API')]
final class AppScrapeCommand extends InvokableServiceCommand
{

    use ConfigureWithAttributes;
    use RunsCommands;
    use RunsProcesses;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
        private AppController $appController
    )
    {
        parent::__construct();
    }

    public function __invoke(
        IO $io,
    ): void {
        foreach (['9789681632540'] as $isbn) {
            $this->appController->searchISBN($isbn);
        }
        foreach ($this->bookRepository->findAll() as $book) {
            $this->appController->searchISBN($book->getIsbn());
        }
        $io->success('app:scrape success.');
    }
}
