<?php

namespace App\Controller;

use App\Rss\Xml;
use App\Entity\Show;
use App\Repository\ShowRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class RssController extends AbstractController
{
    
	
	/**
     * @Route("/rss", name="rss_xml")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rssAction(ShowRepository $showRepository)
    {
		$shows = $showRepository->findAll();
		
        $response = new Response();
        $response->headers->set("Content-type", "text/xml");
        $response->setContent(Xml::generate($shows));
        return $response;

    }
}
