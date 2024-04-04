<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route('/books', name: 'user_books')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $this->render('user/books.html.twig', [
            'userBooks' => $user->getUserBooks(),
        ]);
    }
    #[Route('/export', name: 'user_export_books')]
    #[IsGranted('IS_AUTHENTICATED')]
    public function export(BookService $bookService): Response
    {
        $csv = $bookService->exportBooks($this->getUser());
        return new Response($csv, 200, headers: [
            'Content-Type' => 'text/csv'
        ]);
    }

}

