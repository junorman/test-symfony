<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Security\UsersAuthenticator;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UsersRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    UserAuthenticatorInterface $userAuthenticator, 
    UsersAuthenticator $authenticator, 
    EntityManagerInterface $entityManager, SendMailService $mail): Response
    {
        $user = new Users();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $mail->send(
                'guemby26@gmail.com',
                $user->getEmail(),
                'Activation de votre compte sur le site H-Center ',
                'register',
                compact('user')
            );

            /*return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );*/

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/test2', name: 'app_test2')]
    public function test(Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager){
        $jsonData = array();
        $roles[] = "";

        $email = $request->request->get('registration_form_email');
        $lastname = $request->request->get('registration_form_lastname');
        $firstname = $request->request->get('registration_form_firstname');
        $type_profil = $request->request->get('registration_form_type_profil');
        $datebirth = $request->request->get('registration_form_datebirth');
        $tel = $request->request->get('registration_form_tel');
        $raison_sociale = $request->request->get('registration_form_raison_sociale');
        $siret = $request->request->get('registration_form_siret');
        $statut_jurique = $request->request->get('registration_form_statut_juridique');
        $adresse = $request->request->get('registration_form_adresse');
        $password = $request->request->get('registration_form_plainPassword_first');

        if($type_profil == 1){
            $roles = array('ROLE_ADMIN');
        }else{
            $roles = array('ROLE_USER');
        }

        $pass = iconv('utf-8', 'ascii//TRANSLIT', $password);

        $check_email = $entityManager->getRepository(Users::class)->findByEmail($email);
        $ck = "";
        if(!$check_email){
            $ck = '0';

            $user = new Users();
            $user->setRoles($roles);
            $user->setEmail($email);
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $user->setDatebirth($datebirth);
            $user->setTel($tel);
            $user->setTypeProfil($type_profil);
            $user->setRaisonSociale($raison_sociale);
            $user->setSiret($siret);
            $user->setStatutJuridique($statut_jurique);
            $user->setAdresse($adresse);
            $user->setPassword($userPasswordHasher->hashPassword($user, $pass));

            $entityManager->persist($user);
            $entityManager->flush();

        }else{
            $ck = '1';
        }

        $temp = array(
            'bureau' => $ck
        );
        $jsonData[0] = $temp;

        return new JsonResponse($jsonData);
        //
    }
}
