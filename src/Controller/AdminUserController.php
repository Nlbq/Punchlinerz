<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_users_index")
     */
    public function index(UserRepository $repo): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }


    /**
     * Permet d'afficher le formulaire d'edition
     * 
     * @Route("/admin/users/{id}/edit", name="admin_users_edit")
     *
     * @param User $user
     * @return void
     */
    public function edit(User $user, Request $request, EntityManagerInterface $manager){
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'utilisateur' a bien été enregistré"
            );
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     */
    public function delete(User $user, EntityManagerInterface $manager){
        $manager->remove($user);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'utilisateur' a bien été supprimé"
        );

        return $this->redirectToRoute('admin_users_index');
    }
}
