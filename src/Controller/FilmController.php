<?php

namespace App\Controller;

use App\Entity\Borrowing;
use App\Entity\Film;
use App\Entity\User;
use App\Form\BorrowingType;
use App\Form\FilmType;
use App\Repository\BorrowingRepository;
use App\Repository\FilmRepository;
use App\Service\ApiAccess;
use DateTimeInterface;
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
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="list",  methods={"GET"})
     */
    public function otherIndex(User $user, FilmRepository $filmRepository): Response
    {
        if ($user instanceof User) {
            return $this->render('film/index.html.twig', [
                'films' => $filmRepository->findByOwner($user),
                'shownUser' => $user,
            ]);
        }
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
            if ($form->get('hasFilm')->getData() && $this->getUser() instanceof User) {
                $film->addOwner($this->getUser());
            }
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
     * @Route("/{id}", name="show", methods={"GET", "POST"})
     */
    public function show(
        Film $film,
        BorrowingRepository $borrowingRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $borrowing = new Borrowing();
        $form = $this->createForm(BorrowingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('borrower')->getData() == $this->getUser()) {
                $this->addFlash('notice', 'Vous ne pouvez pas prêter à vous-même.');
            } else {
                if (
                    $form->get('borrower')->getData() instanceof User &&
                    $this->getUser() instanceof User &&
                    $form->get('dateBorrowed')->getData() instanceof DateTimeInterface
                ) {
                    $borrowing->setBorrower($form->get('borrower')->getData())

                        ->setOwner($this->getUser())
                        ->setDateBorrowed($form->get('dateBorrowed')->getData())
                        ->setFilm($film);

                    $entityManager->persist($borrowing);
                    $entityManager->flush();
                }
            }
        }
        if ($this->getUser() instanceof User) {
            $borrowedStatus = $borrowingRepository->findByOwnerFilm($this->getUser(), $film);

            return $this->render('film/show.html.twig', [
                'borrow_form' => $form->createView(),
                'film' => $film,
                'borrowed' => $borrowedStatus,
            ]);
        } else {
            return $this->render('film/show.html.twig');
        }
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Film $film, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmType::class, $film);


        $user = $this->getUser();
        $hasFilm = $form->get('hasFilm');

        if ($film->getOwner()->contains($user)) {
            $hasFilm->setData(true);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $user instanceof User) {
            if (!$film->getOwner()->contains($user) && $hasFilm->getData()) {
                $film->addOwner($user);
            } elseif ($film->getOwner()->contains($user) && !$hasFilm->getData()) {
                $film->removeOwner($user);
            }
            $entityManager->flush();

            return $this->redirectToRoute('film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"POST"})
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
