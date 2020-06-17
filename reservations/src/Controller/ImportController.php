<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Form\ImportType;
use App\Entity\Show;
use App\Entity\Location;
use App\Entity\Artist;
use App\Entity\ArtistType;
use App\Entity\Type;
use App\Entity\Collaboration;
use App\Entity\Representation;
use Doctrine\ORM\ORMException;

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
             
	    $error = '';
		$form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
				
			$data = $form->get('importer')->getData();
			$type = explode(".",$data);

			$filename = '';
			if(!empty($data)){
				 $filename = $data->getClientOriginalName();
			}
  
			if(strtolower(substr($filename, -3)) == 'csv'){	
				$records = [];
				$colaborations = [];
				$collaboration_id = '';
				$entityManager = $this->getDoctrine()->getManager();
				if(!empty($data) && ($file = fopen($data, "r")) !== FALSE){ 
                                 
					while (($csv = fgetcsv($file, 1000, ",")) !== FALSE) 
					{	
						if(!empty($csv[1])){
							
							$firstname = $csv[3];
							$lastname = $csv[4];
							
							//  si firstname est vide on lui donne une valeur 
							if($csv[3] == ''){ 
								$firstname = 'Inconnue';
							}
							
							//  si lastname est vide on lui donne une valeur 
							if($csv[4] == ''){
								$lastname = 'Inconnue';
							}
								
							$artistRepository = $this->getDoctrine()->getRepository(Artist::class);
							$artist = $artistRepository->findOneByFirstname($firstname );
							
								// pour s'assurer on recherche aussi avec Lastname						
							$artistLastname = $artistRepository->findOneByLastname($lastname);
							
								// si $artist n'existe pas
							if(empty($artist) && empty($artistLastname)){
								$artist = new Artist();
								
								$artist->setFirstname($firstname);
								$artist->setLastname($lastname);
								
								$entityManager->persist($artist);
							}
							
							$type = new Type();
							$type->setType("scènographe");
							$entityManager->persist($type);
							
							$artistType = new ArtistType();
							$artistType->setArtist($artist);
							$artistType->setType($type);
							$entityManager->persist($artistType);
							
							$colaboration = new Collaboration();
							$colaboration->setArtistType($artistType);
							
							$entityManager->flush();
							
							$tmp_colaborations['id'] = $csv[0];
							$tmp_colaborations['colaboration'] = $colaboration;
							$colaborations[] = $tmp_colaborations;
							
							try{
								$records[] = [
									'id' => $csv[0],
									'slug' => $csv[1],
									'title' => $csv[2],
									'poster_url' => $csv[5],
									'location' => $csv[6],
									'shedule' => $csv[7],
									'bookable' => $csv[8],
									'price' => $csv[9],
								];
							}
							catch (\Error $e) {
								$error = "Le fichier ".$filename." ne peut pas etre importer" ;
								// $error = $e->getMessage();
							}
						}
					}
					fclose($file);
					
					$locationRepository = $this->getDoctrine()->getRepository(Location::class);
					
					try{
					    foreach ($records as $record) {
							// verifier si le spectacle existe deja
							$repository = $this->getDoctrine()->getRepository(Show::class);
							$existant_show = $repository->findOneByTitle($record['title']);
							$existante_representation = false;
							if(!empty($existant_show)){  // si le spectacle existe
								foreach($existant_show->getRepresentations() as $represent){ 
									if($record['shedule'] == $represent->getSchedule()->format('Y-m-d H:m')){ // si la representation existe
										$existante_representation = true; 
									}
								}
								if(!$existante_representation){  // si la representation n'existe pas
									$representation = new Representation();
									$representation->setSchedule(new \DateTime($record['shedule']));
									$entityManager->persist($representation);
									$existant_show->addRepresentation($representation);
									$entityManager->flush();
								}
							}
							else{	// sinon
								$show = new Show();
								$show->setSlug($record['slug']);
								$show->setTitle($record['title']);
								$show->setPosterUrl($record['poster_url']);
								$show->setLocation($locationRepository->findOneByDesignation($record['location']));
								$show->setBookable($record['bookable']);
								$show->setPrice($record['price']);
								
								$representation = new Representation();
								$representation->setSchedule(new \DateTime($record['shedule']));
								
								foreach($colaborations as $colaboration){  // recuperer la collaboration adequate
									if($colaboration['id'] == $record['id'] && $collaboration_id != $record['id']){ // si cette collaboration n a pas ete encoder
										$colaboration['colaboration']->setShows($show);
										$entityManager->persist($colaboration['colaboration']);
										$show->addCollaboration($colaboration['colaboration']);
										$collaboration_id = $record['id'];
									}
								}
								
								$entityManager->persist($representation);
								$show->addRepresentation($representation);
								
								$entityManager->persist($show);
								$entityManager->flush();
							}
						}
				    }catch(\Exception $e){
					    $error = "Le fichier ".$filename." ne peut pas etre importer" ;
					    // $error = $e->getMessage();
				    }
				    if(empty($error)){
						return $this->render('defaut/index.html.twig', [
							'action' => 'admin',
							'service' => '',
							'importer' => 0,
							'succes' => 'succes',
							'error' => $error,
						]);
				    }
				}
			}
			else{
				$error = "Le fichier ".$filename.'n est pas au format CSV valide';
			}
		}
		
		return $this->render('defaut/index.html.twig', [
			'form' => $form->createView(),
            'action' => 'admin',
			'service' => '',
			'succes' => '',
			'importer' => 1,
            'error' => $error,
        ]);
    }
}
						