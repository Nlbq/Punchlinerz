<?php

namespace App\Controller;

use App\Entity\Punch;
use App\Form\PunchType;
use App\Repository\PunchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPunchController extends AbstractController
{
    /**
     * @Route("/admin/punchs", name="admin_punchs_index")
     */
    public function index(PunchRepository $repo): Response
    {
        return $this->render('admin/punch/index.html.twig', [
            'punchs' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'edition
     * 
     * @Route("/admin/punchs/{id}/edit", name="admin_punchs_edit")
     *
     * @param Punch $punch
     * @return void
     */
    public function edit(Punch $punch, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(PunchType::class, $punch);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($punch);
            $manager->flush();

            $this->addFlash(
                'success',
                "La punchline a bien été enregistrée"
            );
        }

        return $this->render('admin/punch/edit.html.twig', [
            'punch' => $punch,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une punch
     * 
     * @Route("/admin/punchs/{id}/delete", name="admin_punchs_delete")
     */
    public function delete(Punch $punch, EntityManagerInterface $manager){
        $manager->remove($punch);
        $manager->flush();

        $this->addFlash(
            'success',
            "La punchline a bien été supprimée"
        );

        return $this->redirectToRoute('admin_punchs_index');
    }
}
