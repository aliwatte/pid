<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Form\ImportcsvType;
use App\Entity\Show;
use App\Entity\Location;

class ImportController extends AbstractController
{
    /**
     * @Route("/import", name="import")
     */
    public function index(Request $request)
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()->getRole()->getRole() != 'admin') {
            return $this->redirectToRoute('home');
        }
		
		$form = $this->createForm(ImportcsvType::class);
        $form->handleRequest($request);
		
		if ($form->isSubmitted() 
			//&& $form->isValid()
			) {
			$data = $form->get('importer')->getData();
			$records = [];
			if(!empty($data) && ($file = fopen($data, "r")) !== FALSE){
				while (($csv = fgetcsv($file, 1000, ",")) !== FALSE) 
			    {	
					$records[] = [
						'slug' => $csv[1],
						'title' => $csv[2],
						'poster_url' => $csv[3],
						'location' => $csv[4],
						'bookable' => $csv[5],
						'price' => $csv[6],
					];
			    }
				fclose($file);
				
				$entityManager = $this->getDoctrine()->getManager();
				
				$locationRepository = $this->getDoctrine()->getRepository(Location::class);
			
				foreach ($records as $record) {
					$show = new Show();
					$show->setSlug($record['slug']);
					$show->setTitle($record['title']);
					$show->setPosterUrl($record['poster_url']);
					$show->setLocation($locationRepository->findOneByDesignation($record['location']));
					$show->setBookable($record['bookable']);
					$show->setPrice($record['price']);
					$entityManager->persist($show);

				//	$this->addReference($record['slug'], $show);
				
					$entityManager->persist($show);
				}
				
				return $this->render('defaut/index.html.twig', [
					'action' => 'admin',
					'service' => '',
					'importer' => 0,
					'succes' => 'succes',
				]);
			}
		}
		
		return $this->render('defaut/index.html.twig', [
			'form' => $form->createView(),
            'action' => 'admin',
			'service' => '',
			'succes' => '',
			'importer' => 1,
        ]);
    }
}
