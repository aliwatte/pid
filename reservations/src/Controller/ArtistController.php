<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Artist;

class ArtistController extends AbstractController
{
    /**
     * @Route("/artist", name="artist")
     */
    public function index()
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()->getRole()->getRole() != 'admin') {
            return $this->redirectToRoute('home');
        }
		
		$repository = $this->getDoctrine()->getRepository(Artist::class);
        $artists = $repository->findAll();

		return $this->render('defaut/index.html.twig', [
            'action' => 'admin',
			'service' => 'artist',
			'artists' => $artists,
        ]);
    }
}
