<?php

namespace App\Controller;

use App\Entity\Show;
use App\Entity\User;
use App\Entity\Reservation;
use App\Entity\Representation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\RepresentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository, 
							RepresentationRepository $representationRepository): Response
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		$userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
		
		//$reservations = $reservationRepository->findAll();
		$reservations = $reservationRepository->findByUser($userId);
		
		return $this->render('defaut/index.html.twig', [
			'action' => 'reservation',
			'reservations' => $reservations,
			'error' => '',
        ]);
    }
	
	/**
     * @Route("/payement", name="reservation_payement")
     */
	public function payement(){
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		return $this->render('defaut/index.html.twig', [
		//return $this->render('reservation/payement.php', [
			'action' => 'payement',
			'error' => '',
        ]);
	}
	
	/**
     * @Route("/{id}/{show_numero}", name="reservation_reserver", methods={"GET","POST"})
     */
    public function reserver($id, $show_numero, Request $request): Response
    {	
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
		
		$repository = $this->getDoctrine()->getRepository(Show::class);
		$show = $repository->findOneById($id);
		
        if ($form->isSubmitted() && $form->isValid()) {
			
			$userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
			$user_repository = $this->getDoctrine()->getRepository(User::class);
			$user = $user_repository->findOneById($userId);
			$reservation->setUser($user);
			
			$i = 1;
			$representat = '';
			foreach($show->getRepresentations() as $representat){
				if($i == $show_numero){
					$representation = $representat;
				}
				++$i;
			}
			
			$reservation->setRepresentation($representation);
			
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }
		
		return $this->render('defaut/index.html.twig', [
			'action' => 'show_detail',
			'reserver' =>true,
			'reservation' => $reservation,
            'form' => $form->createView(),
			'error' => '',
			'show' => $show,
			'i' => $show_numero,
        ]);
    }
	
    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reservation_index');
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index');
    }
}
