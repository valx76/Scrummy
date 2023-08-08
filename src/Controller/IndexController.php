<?php

namespace App\Controller;

use App\Service\ChoicesSuiteManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly ChoicesSuiteManager $choicesSuiteManager,
    )
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'suites' => $this->choicesSuiteManager->getSuites(),
        ]);
    }
}
