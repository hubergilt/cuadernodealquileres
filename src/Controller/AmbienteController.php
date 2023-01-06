<?php

namespace App\Controller;

use App\Entity\Ambiente;
use App\Form\AmbienteType;
use App\Repository\AmbienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ambiente')]
class AmbienteController extends AbstractController
{
    #[Route('/', name: 'app_ambiente_index', methods: ['GET'])]
    public function index(AmbienteRepository $ambienteRepository): Response
    {
        $lista=array();
        $lista['ambientes']= array();
        $lista['arrendatarios']= array();
        foreach ($ambienteRepository->findAll() as $ambiente){
            array_push($lista['ambientes'], $ambiente);
            array_push($lista['arrendatarios'], $ambiente->getArrendatario());
        }
        return $this->render('ambiente/index.html.twig', [
            'lista' => $lista,
        ]);
    }

    #[Route('/new', name: 'app_ambiente_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AmbienteRepository $ambienteRepository): Response
    {
        $ambiente = new Ambiente();
        $form = $this->createForm(AmbienteType::class, $ambiente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ambienteRepository->add($ambiente);
            return $this->redirectToRoute('app_ambiente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ambiente/new.html.twig', [
            'ambiente' => $ambiente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ambiente_show', methods: ['GET'])]
    public function show(Ambiente $ambiente): Response
    {
        return $this->render('ambiente/show.html.twig', [
            'ambiente' => $ambiente,
            'arrendatario' => $ambiente->getArrendatario(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ambiente_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ambiente $ambiente, AmbienteRepository $ambienteRepository): Response
    {
        $form = $this->createForm(AmbienteType::class, $ambiente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ambienteRepository->add($ambiente);
            return $this->redirectToRoute('app_ambiente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ambiente/edit.html.twig', [
            'ambiente' => $ambiente,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ambiente_delete', methods: ['POST'])]
    public function delete(Request $request, Ambiente $ambiente, AmbienteRepository $ambienteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ambiente->getId(), $request->request->get('_token'))) {
            $ambienteRepository->remove($ambiente);
        }

        return $this->redirectToRoute('app_ambiente_index', [], Response::HTTP_SEE_OTHER);
    }
}
