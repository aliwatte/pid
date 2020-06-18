<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; // Nouveau use
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShowRepository;
use App\Entity\Show;
use App\Entity\Location;
use App\Entity\Representation;
use App\Entity\Type;
use App\Entity\Artist;
use App\Entity\ArtistType;
use App\Entity\Collaboration;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class ApishowsController extends AbstractController
{
	/**
	* @Route("/api/shows", name="apishows_index", methods={"GET"})
	*/
	public function index(ShowRepository $showRepository)
	{
		return $this->json($showRepository->findAll(), 200, [], ['groups' => 'spectacle:read']);
	}
  
	/**
	* @Route("/api/shows", name="apishows_nouveau", methods={"POST"})
	*/
	public function nouveau(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
	{
		$tmp_json = $request->getContent();
	  
		$json = json_decode($tmp_json, true);
		
		$repository = $this->getDoctrine()->getRepository(Show::class);
		$exist_show = $repository->findOneByTitle($json['title']);
		
		if(!empty($exist_show)){  // si le spectacle existe
			$representation = new Representation();
			$representation->setSchedule(new \DateTime($json['representations'][0]['schedule']));
			$entityManager->persist($representation);
			$exist_show->addRepresentation($representation);
			$entityManager->flush();
		}
		else{	// sinon
			$show = new Show();
			$show->setSlug($json['slug']);
			$show->setTitle($json['title']);
			$show->setPosterUrl($json['posterUrl']);
			$locationRepository = $this->getDoctrine()->getRepository(Location::class);
			$show->setLocation($locationRepository->findOneByDesignation('La Samaritaine'));
			$show->setBookable($json['bookable']);
			$show->setPrice($json['price']);
			
			$representation = new Representation();
			$representation->setSchedule(new \DateTime($json['representations'][0]['schedule']));
			
			$entityManager->persist($representation);
			$show->addRepresentation($representation);
			
			$type = new Type();
			$type->setType("scènographe");
			$entityManager->persist($type);
			
			$artist = new Artist();
			$artist->setFirstname('');
			$artist->setLastname('');
			$entityManager->persist($artist);
			
			$artistType = new ArtistType();
			$artistType->setArtist($artist);
			$artistType->setType($type);
			$entityManager->persist($artistType);
			
			$colaboration = new Collaboration();
			$colaboration->setArtistType($artistType);
			
			$entityManager->persist($show);
			$entityManager->flush();
		}
		return $this->json($json, 200, [], []);
	}
	
	/**
	* @Route("/api/shows/{id}", name="apishows_one_spectacle", methods={"GET"})
	*/
	public function one_spectacle($id ,ShowRepository $showRepository)
	{
		$show = $showRepository->findOneById($id);
		if(!empty($show)){
			return $this->json($showRepository->findOneById($id), 200, [], ['groups' => 'spectacle:read']);
		}
		else{
			return new Response("invalide id = ".$id, 400, []);
		}
	}
	
	/**
     * @Route("/api/shows/{id}", name="apishows_delete", methods={"DELETE"})
     */
    public function delete($id ,Request $request)
    {
		$repository = $this->getDoctrine()->getRepository(Show::class);
		$show = $repository->findOneById($id);
		$response = '';
		$code = '';
		if(!empty($show)){
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->remove($show);
			$entityManager->flush();
			$response = "succes";
			$code = 204;
		}else {
			$response = "invalide id = ".$id;
			$code = 500;
		}
		return new Response($response, $code, []);
	}
}