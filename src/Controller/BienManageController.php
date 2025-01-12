<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Form\BienType;
use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bien/admin')]
class BienManageController extends AbstractController
{
    #[Route('/', name: 'app_bien_manage_index', methods: ['GET'])]
    public function index(BienRepository $bienRepository): Response
    {
        return $this->render('bien_manage/index.html.twig', [
            'biens' => $bienRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bien_manage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BienRepository $bienRepository): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_manage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien_manage/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_manage_show', methods: ['GET'])]
    public function show(Bien $bien): Response
    {
        return $this->render('bien_manage/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bien_manage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bienRepository->save($bien, true);

            return $this->redirectToRoute('app_bien_manage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bien_manage/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bien_manage_delete', methods: ['POST'])]
    public function delete(Request $request, Bien $bien, BienRepository $bienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $bienRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_bien_manage_index', [], Response::HTTP_SEE_OTHER);
    }
}