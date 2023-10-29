<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\BookType;

#[Route('/book', name: 'book')]

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/fetch', name: 'fetch')]
    public function fetch(BookRepository $repo): Response{
        $result=$repo->findAll();
        return $this->render('book/list.html.twig',[
            'response'=>$result,
        ]);
    }
//2eme methode tnajem tji fl examen kmala !!!!!!!!!!!!!!

    #[Route('/fetch2', name: 'fetch2')]
    public function fetch2(ManagerRepository $mr): Response{
        $repo=$mr->getRepository(Book::class);
        $result=$repo->findAll();
        return $this->render('book/list.html.twig',[
            'response'=>$result,
        ]);
    }


    #[Route('/addF', name: 'addF')]
public function addF(ManagerRegistry $mr, BookRepository $repo, Request $req): Response
{
    $s = new Book();
    $form = $this->createForm(BookType::class, $s);
    $form->handleRequest($req);
    if ($form->isSubmitted()) {
        $em = $mr->getManager();
        $em->persist($s);
        $em->flush();
        return $this->redirectToRoute('bookfetch');
    }
    return $this->render('book/add.html.twig', [
        'f' => $form->createView(),
    ]);
}

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(ManagerRegistry $mr , $id,BookRepository $repo):Response
    {
      $book=$repo->find($id);

      $em=$mr->getManager();
      $em->remove($book);
      $em->flush();
      return $this->redirectToRoute('bookfetch');

      return new Response('bookremove');
    }
 
 


    #[Route('/edit/{id}', name: 'edit_book')]
public function edit(ManagerRegistry $mr, $id, BookRepository $repo, Request $req): Response
{
    $book = $repo->find($id);

    if (!$book) {
        throw $this->createNotFoundException('Book not found');
    }

    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($req);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $mr->getManager();
        $em->flush();
        return $this->redirectToRoute('bookfetch');
    }

    return $this->render('book/edit.html.twig', [
        'f' => $form->createView(),
    ]);
}
}
