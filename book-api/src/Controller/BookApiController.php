<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// All routes in this controller start with /api/books
#[Route('/api/books')]
class BookApiController extends AbstractController
{
    // GET /api/books - returns all books as JSON
    #[Route('', methods: ['GET'])]
    public function index(BookRepository $bookRepository): JsonResponse
    {
        // Fetch every book from the database
        $books = $bookRepository->findAll();

        // Transform each Book entity into an associative array
        $data = [];
        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'author' => $book->getAuthor(),
                'isbn' => $book->getIsbn(),
                'publishedYear' => $book->getPublishedYear(),
            ];
        }

        return $this->json($data);
    }

    // POST /api/books - creates a new book from JSON request body
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        // Decode the JSON body into a PHP array
        $data = json_decode($request->getContent(), true);

        // Create a new Book entity and set its properties
        $book = new Book();
        $book->setTitle($data['title']);
        $book->setAuthor($data['author']);
        $book->setIsbn($data['isbn']);
        $book->setPublishedYear($data['publishedYear']);

        // Save the book to the database
        $entityManager->persist($book);
        $entityManager->flush();

        // Return the created book with a 201 Created status
        return $this->json([
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isbn' => $book->getIsbn(),
            'publishedYear' => $book->getPublishedYear(),
        ], Response::HTTP_CREATED);
    }

}
