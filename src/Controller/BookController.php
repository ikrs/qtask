<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\Repository\AuthorRepositoryInterface;
use App\Contract\Repository\BookRepositoryInterface;
use App\Exception\CantSaveException;
use App\Exception\EntityNotFoundException;
use App\Form\BookType;
use App\Model\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/books")]
class BookController extends AbstractController
{
    private BookRepositoryInterface $bookRepository;
    private AuthorRepositoryInterface $authorRepository;

    public function __construct(
        BookRepositoryInterface $bookRepository,
        AuthorRepositoryInterface $authorRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    #[Route("/", name: "book_index")]
    public function indexAction(Request $request): Response
    {
        $currentPage = (int)$request->get('page');

        if ($currentPage < 1) {
            $currentPage = 1;
        }

        $books = $this->bookRepository->getMany($currentPage);

        $totalPages = $books->getTotalPages();

        $pageRange = ($totalPages > 1)
            ? range(1, $totalPages)
            : [];

        return $this
            ->render(
                'books/index.html.twig',
                [
                    'books' => $books,
                    'currentPage' => $currentPage,
                    'pageRange' => $pageRange,
                    'pageRangeAction' => $this->generateUrl('book_index'),
                ]
            );
    }

    #[Route("/new", name: "book_create", methods: ["GET", "POST"])]
    public function createAction(Request $request): Response
    {
        $authors = $this->authorRepository
                ->getMany(1, 999, 'firstName');

        $form = $this->createForm(BookType::class, new Book(), [
            'authors' => $authors,
        ]);

        if ($request->isMethod('GET')) {
            return $this
                ->render(
                    'books/new.html.twig',
                    [
                        'form' => $form->createView(),
                    ]
                );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Book $book
             */
            $book = $form->getData();

            try {
                $this->bookRepository->save($book);
                $this->addFlash('success', 'Book added');
            } catch (CantSaveException $exception) {
                $this->addFlash('warning', $exception->getMessage());
            }
        } else {
            $this->addFlash(
                'warning',
                $form->getErrors(true)->__toString()
            );
        }

        return $this->redirectToRoute('book_create');
    }

    #[Route("/{bookId}/delete", name: "book_delete")]
    public function deleteAction(int $bookId, Request $request): RedirectResponse
    {
        $redirectUrl = $request->headers->get('referer');

        try {
            $this->bookRepository->remove($bookId);
        } catch (EntityNotFoundException) {
            $this->addFlash('warning', 'Book not found');
            return new RedirectResponse($redirectUrl);
        }

        $this->addFlash('success', 'Book removed');

        return new RedirectResponse($redirectUrl);
    }
}
