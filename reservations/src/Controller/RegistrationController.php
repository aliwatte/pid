<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Entity\Role;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Controller\sfEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends AbstractController
{
		
    /**
    * @Route("/signin", name="app_signin")
    */
    public function register(Request $request, MailerInterface $mailer,UserPasswordEncoderInterface $passwordEncoder, 
							GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
		if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
		
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
		
		//Définir le rôle par défaut des nouveaux membres
		$repository = $this->getDoctrine()->getRepository(Role::class);
        $role = $repository->findOneByRole('membre');
        $user->setRole($role);
			
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData(),
                )
            );

			$email = (new Email())
				->from('no-replay@no-ip.com')
				->to($user->getEmail())
				->subject('Bienvenue sur le site Projet Réservations!')
				->html('
					<p>
						Votre inscription a été un accepter .
						<a style="text-decoration: non; color:blue;" 
							href="http://localhost:8000/login">Vous connecter ici !</a>
					</p>
				');
				
			$mailer->send($email);
			
			
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
			
            $entityManager->flush();
			
			return $this->redirectToRoute('app_login');
			
            // pour diriger la page d accueil utilisateur authentifier
            /*return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );*/
        }

		return $this->render('defaut/index.html.twig', [
			'registrationForm' => $form->createView(),
			'action' => 'inscription',
			'last_username' => '', 
			'error' => ''
        ]);
    }
	
	/**
    * @Route("/findlogin", name="findlogin")
    */
	public function findlogin(Request $req)
	{
	    if($request->isXmlHttpRequest()) // pour vérifier la présence d'une requete Ajax
	    {		
			if($req->isXMLHttpRequest()){
				$id = $req->get('id');
				$repository = $this->getDoctrine()->getRepository(User::class);
				$user = $repository->findOneById(id);
				return new JsonResponse(array("data" => json_encode($user)));
			}
		}
		return new Response("Nonnn ....");       
	}
	
	
	/**
    * @Route("/validlogin", name="validlogin")
    */
	public function validate_login_json()
	{
		$request = new Request();
		$exist = "true";
		$user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
		
		//Recuperer les login existant dans la bd
		$repository = $this->getDoctrine()->getRepository(User::class);

		/*if ($form->isSubmitted()) {
			$login = $form->get('login')->getData();
			if(!empty($repository->findByLogin($login))) {
				$exist = "false";
			} 
		}*/
		if(isset($_POST['pseudo'])){
			die();
            $login = trim($_POST['login']);
            if(!empty($repository->findByLogin($login))) { 
                        // return false si $username n existe pas
                $exist = "false";
            }
        }
		
		return new Response($exist);
	}
}
