<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Punch;

use App\Form\PunchType;
use App\Repository\PunchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $punch = new Punch();

        $form = $this->createForm(PunchType::class, $punch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $punch->setAuthor($this->getUser());

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
     * @Security("is_granted('ROLE_USER') and user === punch.getAuthor()")
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

    /**
     * Supprimer une annonce
     * 
     * @Route("/punchs/{id}/delete", name="punchs_delete")
     * @Security("is_granted('ROLE_USER') and user == punch.getAuthor()")
     *
     * @param Punch $punch
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Punch $punch, EntityManagerInterface $manager) {
        $manager->remove($punch);
        $manager->flush();

        $this->addFlash(
            'success',
            "La punch a bien été supprimée, et c'est pas du luxe"
        );

        return $this->redirectToRoute("punchs_index");
    }

}
