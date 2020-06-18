<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Role;
use App\Form\UserType;
use App\Form\PaswordType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()->getRole()->getRole() != 'admin') {
            return $this->redirectToRoute('home');
        }
		
		return $this->render('defaut/index.html.twig', [
			'action' => 'admin',
			'service' => 'user',
			'users' => $userRepository->findAll(),
        ]);
		
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()->getRole()->getRole() != 'admin') {
            return $this->redirectToRoute('home');
        }
		
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
			
			// encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('login')->getData()
                )
            );
			
			//Définir le rôle par défaut des nouveaux membres
			$repository = $this->getDoctrine()->getRepository(Role::class);
			$role = $repository->findOneByRole('membre');
			$user->setRole($role);
		
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user');
        }
		
		return $this->render('defaut/index.html.twig', [
			'action' => 'nouveau',
			'user' => $user,
            'form' => $form->createView(),
			'pass' => 0,
			'role' => 'admin',
			'admin' => 1,
			'pass' => 'new',
        ]);
		
		
    }

    /**
     * @Route("/{id}/{admin}", name="user_profil", methods={"GET"})
     */
    public function show($admin, User $user): Response
    {
		if (empty($this->getUser())) {
            return $this->redirectToRoute('home');
        }
		
		return $this->render('defaut/index.html.twig', [
			'action' => 'user_profil',
			'user' => $user,
			'last_username' => '', 
			'error' => '',
			'admin' => $admin,
        ]);
    }

    /**
	* @Route("/{id}/{pass}/{admin}/edit", name="user_edit", methods={"GET","POST"})
    */
    public function edit($pass, $admin, Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()->getRole()->getRole() != 'admin' && $this->getUser()->getId()!== $user->getId()) {
            return $this->redirectToRoute('home');
        }
		
		$errors = [];
		
        $form = $this->createForm(UserType::class, $user);
		if($pass == 1){
			$form = $this->createForm(PaswordType::class, $user);
		}
		
		//$valide = false;
		
        $form->handleRequest($request);
		
        if ($form->isSubmitted()&& $form->isValid()) {
			//L'ancien mot de passe a été fourni
			if($pass == 1){
				$oldPassword = $form->get('password')->getData();
				
				if($oldPassword != '') {
					$newPassword = $form->get('newPassword')->getData();
					$confPassword = $form->get('confPassword')->getData();
					
					//L'ancien mot de passe correspond
					if($passwordEncoder->isPasswordValid($user, $oldPassword)) {

						//Le nouveau mot de passe est confirmé
						if($newPassword == $confPassword) {
							die();
							//Crypter le nouveau mot de passe
							$user->setPassword(
								$passwordEncoder->encodePassword(
									$user,
									$form->get('newPassword')->getData()
								)
							);
						}
						else{
							$errors = $form['confPassword']->getErrors();
						}
					}
				}
			}
			
			if(count($errors) == 0){
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($user);
				$manager->flush();
				
				return $this->render('defaut/index.html.twig', [
					'action' => 'user_profil',
					'user' => $user,
					'last_username' => '', 
					'error' => '',
					'admin' => $admin,
				]);
			}
        }
		else{
			$errors = $form->getErrors();
		}
		 
		return $this->render('defaut/index.html.twig', [
			'form' => $form->createView(),
			'action' => 'user_profil_edit',
			'user' => $user,
			'last_username' => '', 
			'errors' => $errors,
			'pass' => $pass,   // pour afficher les champ du password
			'admin' => $admin,
		]);
    }


    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete($id ,Request $request, User $user, Session $session): Response
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$entityManager = $this->getDoctrine()->getManager();
		$membre = '';
		if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
			$repository = $this->getDoctrine()->getRepository(User::class);
			//$entityManager = $this->getDoctrine()->getManager();
			$membre = $repository->findOneById($id);
		}
		else {
			return $this->redirectToRoute('home');
		}
		
		$role = $this->getUser()->getRole()->getRole();
		if($membre != '' && $role == 'admin' &&
					$this->getUser()->getId()!= $user->getId()) { // pour l admin
			$entityManager->remove($membre);
			$entityManager->flush();
			return $this->redirectToRoute('user');
        }
		else{	// pour un utilisateur connecte
			$session = $this->get('session');
			$this->get('security.token_storage')->setToken(null); // forcer le logout de l utilisateur  connecté  
			$entityManager->remove($membre);
			$entityManager->flush();
			session_write_close();  //fermer la session
			return $this->redirectToRoute('home');
		}
    }
}
