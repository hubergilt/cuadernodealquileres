<?php

namespace App\Controller;

use App\Entity\Arrendatario;
use App\Form\ArrendatarioType;
use App\Repository\ArrendatarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/arrendatario')]
class ArrendatarioController extends AbstractController
{
    #[Route('/', name: 'app_arrendatario_index', methods: ['GET'])]
    public function index(ArrendatarioRepository $arrendatarioRepository): Response
    {
        return $this->render('arrendatario/index.html.twig', [
            'arrendatarios' => $arrendatarioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_arrendatario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ArrendatarioRepository $arrendatarioRepository): Response
    {
        $arrendatario = new Arrendatario();
        $form = $this->createForm(ArrendatarioType::class, $arrendatario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrendatarioRepository->add($arrendatario);
            return $this->redirectToRoute('app_arrendatario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('arrendatario/new.html.twig', [
            'arrendatario' => $arrendatario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_arrendatario_show', methods: ['GET'])]
    public function show(Arrendatario $arrendatario): Response
    {
        return $this->render('arrendatario/show.html.twig', [
            'arrendatario' => $arrendatario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_arrendatario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Arrendatario $arrendatario, ArrendatarioRepository $arrendatarioRepository): Response
    {
        $form = $this->createForm(ArrendatarioType::class, $arrendatario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $arrendatarioRepository->add($arrendatario);
            return $this->redirectToRoute('app_arrendatario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('arrendatario/edit.html.twig', [
            'arrendatario' => $arrendatario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_arrendatario_delete', methods: ['POST'])]
    public function delete(Request $request, Arrendatario $arrendatario, ArrendatarioRepository $arrendatarioRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$arrendatario->getId(), $request->request->get('_token'))) {
            $arrendatarioRepository->remove($arrendatario);
        }

        return $this->redirectToRoute('app_arrendatario_index', [], Response::HTTP_SEE_OTHER);
    }

}
