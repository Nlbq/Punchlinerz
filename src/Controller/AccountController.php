<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /**
     * Afficher formulaire de connexion
     * 
     * @Route("/login", name="account_login")
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Se déconnecter
     * 
     *@Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){}

    /**
     * Affiche un formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher){
        
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            
            $user->setRoles([]);
            $password = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($password);
            $picture="https://picsum.photos/80";
            $user->setPicture($picture);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé, vous pouvez maintenant vous connecter"
            );

            return $this->redirectToRoute('account_login');
        }
        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Formulaire de modification du profil
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){

        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Ton profil a bien été modifié"
            );

        }
        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Afficher le profil de l'utilisateur connecté
     * 
     * @Route("/account", name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig', [
            'user' =>$this->getUser()
        ]);
    }

    /**
     * Afficher formulaire de connexion
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */


    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator): Response
    {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return new Response($errorsString);
    }

        if($form->isSubmitted() && $form->isValid()){

            if(password_verify($passwordUpdate->getOldPassword(), $user->getPassword())){
    
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $password = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($password);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Ton mot de passe a bien été modifié"
                );

                return $this->redirectToRoute(('home'));
            }

        }
   
        return $this->render('account/password.html.twig', [
            'form'=> $form->createView()
        ]);
}
}