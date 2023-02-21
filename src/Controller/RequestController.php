<?php

namespace App\Controller;

use App\Entity\Request;
use App\Form\RequestFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Response, Request as Html_Request};

class RequestController extends AbstractController
{
    #[Route('/request', name: 'app_request')]
    public function index(ManagerRegistry $doctrine): Response
    {
	    $requests = $doctrine->getRepository(Request::class)->findAll();

        return $this->render('request/index.html.twig', [
            'title' => 'Liste des demandes',
	        'requests' => $requests,
        ]);
    }


	#[Route('/request/new', name: 'app_request_new')]
	public function new(Html_Request $html_request, ManagerRegistry $doctrine): Response
	{
		$entityManager = $doctrine->getManager();
		$request = new Request();

		$form = $this->createForm(RequestFormType::class, $request);

		$form->handleRequest($html_request);
		if ($form->isSubmitted() && $form->isValid()) {
			$request = $form->getData();
			$request->setStatus('waiting');


			$entityManager->persist($request);
			$entityManager->flush();

			$this->addFlash(
				'success',
				'Votre demande a bien était crée.'
			);

			return $this->redirectToRoute('app_request');
		}

		return $this->render('request/new.html.twig', [
			'title' => 'Creation d\'une demande',
			'form' => $form,
		]);
	}

	#[Route('/request/delete', name: 'app_request_delete')]
	public function delete(): Response
	{
		return $this->render('request/index.html.twig', [
			'title' => 'Suppression d\'une demande',
		]);
	}

	#[Route('/request/edit', name: 'app_request_edit')]
	public function edit(): Response
	{
		return $this->render('request/index.html.twig', [
			'title' => 'Modification de la demande N°',
		]);
	}



	#[Route('/request/{id}', name: 'app_request_detail')]
	public function detail(): Response
	{
		return $this->render('request/index.html.twig', [
			'title' => 'Demande N°',
		]);
	}
}
