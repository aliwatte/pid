<?php

namespace App\Controller;

use App\Entity\Show;
use App\Repository\ShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class SpectaclesApiController extends AbstractController 
{
    /**
     * @Route("/spectacles/api", name="spectacles_api_index", methods={"GET"})
     */
    public function index(ShowRepository $showRepository)
    {
		return $this->json($showRepository->findAll(), 200, [], ['groups' => 'spectacle:read']);
    }
	
	/**
     * @Route("/spectacles/api", name="spectacles_api_store", methods={"POST"})
     */
	public function store(Request $request, SerializerInterface $serializer, 
								  EntityManagerInterface $em, ValidatorInterface $validator){
		$jsonReçue = $request->getContent();
		
		try{
			$show = $serializer->deserialize($jsonReçue, Show::class, 'json');
			
			//$show->setCreatedAt(new \DateTime());
			
			$errors = $validator->validate($show);
			
			if(count($errors) > 0){
				return $this->json($errors, 400);
			}
			
			$em->persist($show);
			$em->flush();
			
			return $this->json($show, 201, [], ['groups' => 'spectacle:read']);
		}
		catch (NotEncodableValueException $e){
			return $this->json([
				'status' => 400,
				'message' => $e->getMessage()
			], 400);
		}
	}
	
	/**
	 * @Route("/spectacles/api/{id}", name="spectacles_api_spectacle", methods={"GET"})
	 */
	public function spectacle($id, ShowRepository $showRepository)
	{
		$show = $showRepository->findOneById($id);
		if(!$show){
			return new Response('echec, spectacle non trouve', 201);
		}
		
		return $this->json($show, 200, [], ['groups' => 'spectacle:read']);
	}
	
	/**
	 * @Route("/spectacles/api/{id}", name="spectacles_api_edit", methods={"PUT"})
	 */
	public function editSpectacle(?Show $show, Request $request)
	{
			// On décode les données envoyées
			$donnees = json_decode($request->getContent());

			// Si show n'est pas trouvé
			if(!$show){
				return new Response('echec, spectacle non trouve', 201);
			}

			$show->setSlug($donnees->slug);
			$show->setTitle($donnees->title);
			$show->setBookable($donnees->bookable);
			$show->setPrice($donnees->price);

			// On sauvegarde en base
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($show);
			$entityManager->flush();

			return new Response('done', 200);
	}
	
	/**
    * @Route("/spectacles/api/{id}", name="spectacles_api_remove", methods={"DELETE"})
    */ 
	public function removeSpectacle($id, ShowRepository $showRepository)
	{	
		$show = $showRepository->findOneById($id);
		if(!$show){
			return new Response('echec, spectacle non trouve', 201);
		}
		
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($show);
		$entityManager->flush();
		
		return new Response('done', 200);
	}
}
