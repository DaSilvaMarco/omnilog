<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Note;
use App\Entity\File;
use App\Form\NoteType;
use App\Repository\NoteRepository;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/')]
class NoteController extends AbstractController
{
    #[Route('/', name: 'note_index', methods: ['GET'])]
    public function index(NoteRepository $noteRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $noteRepository->findAll();

        $notes = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('note/index.html.twig', [
            'notes' => $notes
        ]);
    }

    #[Route('/new', name: 'note_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('files')->getData();

            foreach($files as $file) {

                $newFilename = md5(uniqid()) . '-' . $file->guessExtension();

                $file->move(
                    $this->getParameter('files_directory'),
                    $newFilename
                ); 

                $fl = new File();
                $fl->setUrl($newFilename);
                $note->addFile($fl);

            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();

            return $this->redirectToRoute('note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/new.html.twig', [
            'note' => $note,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'note_show', methods: ['GET'])]
    public function show(Note $note, FileRepository $fileRepository): Response
    {
        $files= $fileRepository->findBy(['note'=>$note]);

        return $this->render('note/show.html.twig', [
            'note' => $note,
            'files' => $files
        ]);
    }

    #[Route('/{id}/edit', name: 'note_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Note $note, FileRepository $fileRepository): Response
    {

        $files= $fileRepository->findBy(['note'=>$note]);


        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('files')->getData();

            foreach($files as $file) {

                $newFilename = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $this->getParameter('files_directory'),
                    $newFilename
                ); 

                $fl = new File();
                $fl->setUrl($newFilename);
                $note->addFile($fl);

            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('note_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('note/edit.html.twig', [
            'note' => $note,
            'form' => $form,
            'files' => $files
        ]);
    }

    #[Route('/{id}', name: 'note_delete', methods: ['POST'])]
    public function delete(Request $request, Note $note): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), (string) $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($note);
            $entityManager->flush();
        }

        return $this->redirectToRoute('note_index', [], Response::HTTP_SEE_OTHER);
    }
}
