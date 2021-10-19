<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\Repository\AuthorRepositoryInterface;
use App\Exception\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/authors")]
class AuthorController extends AbstractController
{
    private AuthorRepositoryInterface $authorRepository;

    public function __construct(AuthorRepositoryInterface $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    #[Route("/", name: "author_index")]
    public function indexAction(Request $request): Response
    {
        $currentPage = (int)$request->get('page');

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $authors = $this->authorRepository->getMany($currentPage);

        $totalPages = $authors->getTotalPages();

        $pageRange = ($totalPages > 1)
            ? range(1, $totalPages)
            : [];

        return $this
            ->render(
                'authors/index.html.twig',
                [
                    'authors' => $authors,
                    'currentPage' => $currentPage,
                    'pageRange' => $pageRange,
                    'pageRangeAction' => $this->generateUrl('author_index'),
                ]
            );
    }

    #[Route("/{authorId}", name: "author_view")]
    public function viewAction(int $authorId): Response
    {
        try {
            $author = $this->authorRepository->getOne($authorId);
        } catch (EntityNotFoundException) {
            $this->addFlash('warning', 'Author not found');
            return new RedirectResponse($this->generateUrl('author_index'));
        }

        return $this
            ->render(
                'authors/view.html.twig',
                [
                    'author' => $author,
                ]
            );
    }

    #[Route("/{authorId}/delete", name: "author_delete")]
    public function deleteAction(int $authorId): RedirectResponse
    {
        try {
            $author = $this->authorRepository->getOne($authorId);

            if (!$author->hasBooks()) {
                $this->authorRepository->remove($authorId);
                $this->addFlash('success', 'Author removed');
            } else {
                $this->addFlash('warning', 'Author must have no books before removal');
            }
        } catch (EntityNotFoundException) {
            $this->addFlash('warning', 'Author not found');
        }

        return new RedirectResponse($this->generateUrl('author_index'));
    }
}
