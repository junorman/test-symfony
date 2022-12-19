<?php

namespace App\Controller;

use App\Entity\Bureaux;
use App\Form\BureauxType;
use App\Repository\BureauxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/bureaux')]
class BureauxController extends AbstractController
{
    #[Route('/', name: 'app_bureaux_index', methods: ['GET'])]
    public function index(BureauxRepository $bureauxRepository): Response
    {
        return $this->render('bureaux/index.html.twig', [
            'bureauxes' => $bureauxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bureaux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BureauxRepository $bureauxRepository): Response
    {
        $bureaux = new Bureaux();
        $form = $this->createForm(BureauxType::class, $bureaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bureauxRepository->save($bureaux, true);

            return $this->redirectToRoute('app_bureaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bureaux/new.html.twig', [
            'bureaux' => $bureaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bureaux_show', methods: ['GET'])]
    public function show(Bureaux $bureaux): Response
    {
        return $this->render('bureaux/show.html.twig', [
            'bureaux' => $bureaux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bureaux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bureaux $bureaux, BureauxRepository $bureauxRepository): Response
    {
        $form = $this->createForm(BureauxType::class, $bureaux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bureauxRepository->save($bureaux, true);

            return $this->redirectToRoute('app_bureaux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bureaux/edit.html.twig', [
            'bureaux' => $bureaux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bureaux_delete', methods: ['POST'])]
    public function delete(Request $request, Bureaux $bureaux, BureauxRepository $bureauxRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bureaux->getId(), $request->request->get('_token'))) {
            $bureauxRepository->remove($bureaux, true);
        }

        return $this->redirectToRoute('app_bureaux_index', [], Response::HTTP_SEE_OTHER);
    }
}
