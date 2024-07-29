<?php

namespace App\Controller;

use App\Entity\Range;
use App\Form\RangeType;
use App\Repository\RangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/range')]
class RangeController extends AbstractController
{
    #[Route('/', name: 'app_range_index', methods: ['GET'])]
    public function index(RangeRepository $rangeRepository): Response
    {
        return $this->render('range/index.html.twig', [
            'ranges' => $rangeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_range_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $range = new Range();
        $form = $this->createForm(RangeType::class, $range);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($range);
            $entityManager->flush();

            return $this->redirectToRoute('app_range_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('range/new.html.twig', [
            'range' => $range,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_range_show', methods: ['GET'])]
    public function show(Range $range): Response
    {
        return $this->render('range/show.html.twig', [
            'range' => $range,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_range_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Range $range, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RangeType::class, $range);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_range_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('range/edit.html.twig', [
            'range' => $range,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_range_delete', methods: ['POST'])]
    public function delete(Request $request, Range $range, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$range->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($range);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_range_index', [], Response::HTTP_SEE_OTHER);
    }
}
