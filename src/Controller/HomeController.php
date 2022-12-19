<?php

namespace App\Controller;
use App\Entity\Bureaux;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\BureauxRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(BureauxRepository $bureauxRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'bureaux' => $bureauxRepository->findAll(),
        ]);
    }

    #[Route('/test', name: 'test_ajax')]
    public function getData(Request $request,
    BureauxRepository $bureauxRepository, 
    EntityManagerInterface $entityManager): Response
    {   
        $repository = $entityManager->getRepository(Bureaux::class);
        $capacite = $request->request->get('capacite');
        $surface = $request->request->get('surface');
        $prix = $request->request->get('prix');
        
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Bureaux p
            WHERE p.prix Like :prix
            OR p.surface Like :surface
            OR p.capacite Like :capacite
            ORDER BY p.prix ASC'
        )->setParameter('prix', '%' . $prix . '%')
        ->setParameter('surface', '%' . $surface . '%')
        ->setParameter('capacite', '%' . $capacite . '%');

        // returns an array of Product objects
        $bureaux = $query->getResult();

        //dd($bureaux);

        /*$bureaux = $repository->findBy(
            [
                'capacite' => $capacite,
                
            ]
        );*/
        

        /*$bureaux = $repository->findBy(
            [
                'date_debut' => '2022-12-16',
                'date_fin' => '2022-12-23',
            ]
        );*/

        //dd($bureaux);
        //$bureaux = $bureauxRepository->findAll();
        $jsonData = array();
        $idx = 0;

        foreach ($bureaux as $key => $bureau) {
            $temp = array(
                'categories' => $bureau->getCategories()->getLibelle(),
                'id' => $bureau->getId(),
                'nom' => $bureau->getNom(),
                'image' => $bureau->getImage(),
                'prix' => $bureau->getPrix(),
                'surface' => $bureau->getSurface(),
                'capacite' => $bureau->getCapacite(),
                'ht' => $bureau->getHt()
            );
            $jsonData[$idx++] = $temp;
        }
        return new JsonResponse($jsonData);
    }

    #[Route('/test3', name: 'test3_ajax')]
    public function getData3(Request $request,
    BureauxRepository $bureauxRepository, 
    EntityManagerInterface $entityManager): Response
    {   
        $repository = $entityManager->getRepository(Bureaux::class);
        $date_debut = $request->request->get('date_debut');
        $date_fin = $request->request->get('date_fin');
        
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Bureaux p
            WHERE p.debut Like :date_debut
            OR p.fin Like :date_fin
            ORDER BY p.id ASC'
        )->setParameter('date_debut', '%' . $date_debut . '%')
        ->setParameter('date_fin', '%' . $date_fin . '%');

        // returns an array of Product objects
        $bureaux = $query->getResult();

        //dd($bureaux);

        /*$bureaux = $repository->findBy(
            [
                'capacite' => $capacite,
                
            ]
        );*/
        

        /*$bureaux = $repository->findBy(
            [
                'date_debut' => '2022-12-16',
                'date_fin' => '2022-12-23',
            ]
        );*/

        //dd($bureaux);
        //$bureaux = $bureauxRepository->findAll();
        $jsonData = array();
        $idx = 0;

        foreach ($bureaux as $key => $bureau) {
            $temp = array(
                'categories' => $bureau->getCategories()->getLibelle(),
                'id' => $bureau->getId(),
                'nom' => $bureau->getNom(),
                'image' => $bureau->getImage(),
                'prix' => $bureau->getPrix(),
                'surface' => $bureau->getSurface(),
                'capacite' => $bureau->getCapacite(),
                'ht' => $bureau->getHt()
            );
            $jsonData[$idx++] = $temp;
        }
        return new JsonResponse($jsonData);
    }
}
