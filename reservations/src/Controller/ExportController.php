<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Entity\Show;
use App\Repository\ShowRepository;

class ExportController extends AbstractController
{
	/**
     * @Route("/export", name="export")
     */
	public function export()
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->getUser()->getRole()->getRole() != 'admin') {
            return $this->redirectToRoute('home');
        }
		
		$repository = $this->getDoctrine()->getRepository(Show::class);
        $results = $repository->findAll();

        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($results) {
                $handle = fopen('php://output', 'r+');
                foreach ($results as $row) {
					foreach($row->getRepresentations() as $represent){
						$author_name = null;
						$author_firstname = null;
						foreach($row->getAuthors() as $author){
							$author_name = $author->getFirstname();
							$author_firstname = $author->getLastname();
						}
						$data = array(  //array liste a exporter
							$row->getId(),
							$row->getSlug(),
							$row->getTitle(),
							$author_name,
							$author_firstname,
							$row->getPosterUrl(),
							$row->getLocation()->getDesignation(),
							$represent->getSchedule()->format('Y-m-d H:m'),
							$row->getBookable(),
							$row->getPrice(),
						);
						fputcsv($handle, $data);
					}
                }
                fclose($handle);
            }
        );
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="spectacles.csv"');

        return $response;
    }
}
