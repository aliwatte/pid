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
                    //array list fields you need to export
                    $data = array(
                        $row->getId(),
                        $row->getSlug(),
						$row->getTitle(),
						$row->getPosterUrl(),
						$row->getLocation()->getDesignation(),
						$row->getBookable(),
						$row->getPrice(),
                    );
                    fputcsv($handle, $data);
                }
                fclose($handle);
            }
        );
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="spectacles.csv"');

        return $response;
    }
}
