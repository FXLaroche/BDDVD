<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(FilmRepository $filmRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('film_list', ['id' => $this->getUser()->getId()]);
        }
        return $this->render('home/index.html.twig', [
            'title' => '',
            'filmCount' => count($filmRepository->findAll()),
            ]);
    }
}
