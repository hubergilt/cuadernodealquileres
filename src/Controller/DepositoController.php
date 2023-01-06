<?php

namespace App\Controller;

use App\Entity\Deposito;
use App\Form\DepositoType;
use App\Repository\DepositoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;

#[Route('/deposito')]
class DepositoController extends AbstractController
{
    #[Route('/', name: 'app_deposito_index', methods: ['GET'])]
    public function index(DepositoRepository $depositoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $depositoRepository->queryToFindAllWithJoins();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('deposito/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/new', name: 'app_deposito_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DepositoRepository $depositoRepository): Response
    {
        $deposito = new Deposito();
        $form = $this->createForm(DepositoType::class, $deposito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depositoRepository->add($deposito);
            return $this->redirectToRoute('app_deposito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('deposito/new.html.twig', [
            'deposito' => $deposito,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_deposito_show', methods: ['GET'])]
    public function show(Deposito $deposito): Response
    {
        return $this->render('deposito/show.html.twig', [
            'deposito' => $deposito,
            'arrendatario' => $deposito->getArrendatario(),
            'ambiente' => $deposito->getAmbiente(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_deposito_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Deposito $deposito, DepositoRepository $depositoRepository): Response
    {
        $form = $this->createForm(DepositoType::class, $deposito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $depositoRepository->add($deposito);
            return $this->redirectToRoute('app_deposito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('deposito/edit.html.twig', [
            'deposito' => $deposito,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_deposito_delete', methods: ['POST'])]
    public function delete(Request $request, Deposito $deposito, DepositoRepository $depositoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deposito->getId(), $request->request->get('_token'))) {
            $depositoRepository->remove($deposito);
        }

        return $this->redirectToRoute('app_deposito_index', [], Response::HTTP_SEE_OTHER);
    }
}
