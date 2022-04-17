<?php

namespace App\Controller;

use App\Entity\Punch;
use App\Form\PunchType;
use App\Repository\PunchRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class PunchController extends AbstractController
{
    /**
     * @Route("/punchs", name="punchs_index")
     */
    public function index(PunchRepository $repo): Response{
        $punchs = $repo->findAll();
        
        return $this->render('punch/index.html.twig', [
            'punchs' => $punchs,
        ]);
    }

    /**
     * Créer une punch
     * @Route("/punchs/new", name="punchs_create")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $punch = new Punch();

        $form = $this->createForm(PunchType::class, $punch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($punch);
            $manager->flush();

            $this->addFlash(
                'success',
                "La punchline a bien été enregistrée, même si elle est un peu claquée"
            );

            return $this->redirectToRoute('punchs_show', [
                'id' => $punch->getId()
            ]);
        }

        return $this->render('punch/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * 
     * @Route("/punchs/{id}/edit", name="punchs_edit")
     *
     * @return Response
     */
    public function edit(Punch $punch, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(PunchType::class, $punch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($punch);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de la punch' ont bien été enregistrées"
            );

            return $this->redirectToRoute('punchs_show', [
                'id' => $punch->getId()
            ]);
        }

        return $this->render('punch/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/punchs/{id}", name="punchs_show")
     */
    public function show(Punch $punch){
        return $this->render('punch/show.html.twig', [
            'punch' => $punch
        ]);
    }

}
