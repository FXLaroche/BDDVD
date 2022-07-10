<?php

namespace App\Controller;

use App\Entity\Borrowing;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BorrowingController extends AbstractController
{
    /**
     * @Route("/borrowing/delete/{id}", name="borrowing_delete", methods={"POST"})
     */
    public function delete(
        HttpFoundationRequest $request,
        Borrowing $borrowing,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $borrowing->getId(), (string)$request->request->get('_token'))) {
            $entityManager->remove($borrowing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('film_show', [
            'id' => $borrowing->getFilm()->getId(),
            'title' => 'Borrowing',
        ], Response::HTTP_SEE_OTHER);
    }
}
