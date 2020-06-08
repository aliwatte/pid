<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShowRepository;
use App\Entity\Show;
use App\Entity\Representation;

class ProchainspectactleController extends AbstractController
{
	
    /**
    * @Route("/prochainspectactle", name="prochainspectactle")
    */
    public function index()
    {	
		$repository = $this->getDoctrine()->getRepository(Show::class);
		$prochainnements = $repository->findAll();
		$representationRepository = $this->getDoctrine()->getRepository(Representation::class);
		$representation = $representationRepository->findBy(array(),array('schedule' => 'ASC'));
		/*
		foreach($representation as $rep){
			var_dump($rep->getShow()->getTitle()." :");
			var_dump($rep->getSchedule());
		}	
		die();
		*/
        return $this->render('defaut/index.html.twig', [
			'controller_name' => 'ProchainspectactleController',
			'action' => 'prochain',
			'prochainnements' => $prochainnements,
		]);
    }
}
