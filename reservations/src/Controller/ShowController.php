<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Show;
use App\Repository\ShowRepository;

use Curl;

class ShowController extends AbstractController
{
    /**
     * @Route("/show", name="show")
     */
    public function index(SerializerInterface $serializer,EntityManagerInterface $em)
    {   
		$repository = $this->getDoctrine()->getRepository(Show::class);

        $shows = $repository->findAll();
		return $this->render('defaut/index.html.twig', [
			'shows' => $shows,
            'titre' => 'Liste des spectacles',
            'action' => 'show',
        ]);
    }
	
	/**
     * @Route("/new", name="show_new", methods={"GET","POST"})
	*/
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', NULL, 'Accès interdit');

        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $show->setSlug($slugify->slugify($show->getTitle()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($show);
            $entityManager->flush();

            return $this->redirectToRoute('show_index');
        }

        return $this->render('show/new.html.twig', [
            'show' => $show,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show_show", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function show(Show $show)
    {	
		return $this->render('defaut/index.html.twig', [
			'action' => 'show_detail',
			'reserver' =>false,
			'show' => $show,
			'i' => '',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="show_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Show $show): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', NULL, 'Accès interdit');

        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('show_index', [
                'id' => $show->getId(),
            ]);
        }

        return $this->render('show/edit.html.twig', [
            'show' => $show,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Show $show): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', NULL, 'Accès interdit');

        if ($this->isCsrfTokenValid('delete'.$show->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($show);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_index');
	}
}
