<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\User;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use App\Service\ApiAccess;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/film", name="film_")
 */
class FilmController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(FilmRepository $filmRepository): Response
    {
        $user = $this->getUser();

        if ($user instanceof User) {
            return $this->render('film/index.html.twig', [
                'films' => $filmRepository->findByOwner($user),
            ]);
        }
        return $this->render('film/index.html.twig');
    }

    /**
     * @Route("/myfilms", name="mylist",  methods={"GET"})
     */
    public function myIndex(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('film/new.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchFilms(Request $request, ApiAccess $apiAccess): ?JsonResponse
    {
        $filmList = [];
        $string = "Lord";
        if (null !== $request->request->get('searchTitle')) {
            $string = (string)$request->request->get('searchTitle');

            if (is_string($this->getParameter('app.api_key'))) {
                $apiKey = $this->getParameter('app.api_key');

                $filmList = $apiAccess->searchApi($string, $apiKey);

                return new JsonResponse($filmList);
            }
        }
        return null;
    }

    /**
     * @Route("/get", name="get")
     */
    public function getFilm(Request $request, ApiAccess $apiAccess): ?JsonResponse
    {
        $film = "";
        if (null !== $request->request->get('getFilm')) {
            $filmId = trim((string)$request->request->get('getFilm'));

            if (is_string($this->getParameter('app.api_key'))) {
                $apiKey = $this->getParameter('app.api_key');

                $film = $apiAccess->getApi((string)$filmId, (string)$apiKey);

                return new JsonResponse($film);
            }
        }
        return null;
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $film->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($film);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_index', [], Response::HTTP_SEE_OTHER);
    }
}
