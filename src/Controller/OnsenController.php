<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OnsenController extends AbstractController
{
    #[Route(path: '/', name: 'project_mobile', methods: ['GET'])]
    public function mobile(Request $request): Response
    {
        $templates = [];
        foreach (['login','blank','scan','list'] as $route) {
            $templates[$route] = $this->renderView("mobile/$route.html.twig");
        }
        return $this->render('app/index.html.twig', [
            'templates' => $templates,
        ]);

    }

    // do we need the project here? Or is it all from the database?  I guess from dexie!
    #[Route(path: '/mobile/{page}', name: 'project_page', methods: ['GET'])]
    public function page(Request $request, string $page): Response
    {
        $view = "mobile/{$page}.html.twig";
        return $this->render($view);
    }
}
