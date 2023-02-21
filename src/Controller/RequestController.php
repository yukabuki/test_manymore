<?php

namespace App\Controller;

use App\Entity\Request;
use App\Form\RequestFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Response, Request as Html_Request};
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RequestController extends AbstractController
{
    #[Route('/request', name: 'app_request')]
    #[IsGranted('ROLE_USER')]
    public function index(ManagerRegistry $doctrine): Response
    {
	    $requests = $doctrine->getRepository(Request::class)->findAll();

        return $this->render('request/index.html.twig', [
            'title' => 'Liste des demandes',
	        'requests' => $requests,
        ]);
    }


	#[Route('/request/new', name: 'app_request_new')]
	#[IsGranted('ROLE_ADMIN')]
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

	#[Route('/request/delete/{id}', name: 'app_request_delete')]
	#[IsGranted('ROLE_ADMIN')]
	public function delete(int $id, ManagerRegistry $doctrine): Response
	{
		$request = $doctrine->getRepository(Request::class)->find($id);
		if (!$request) {
			throw $this->createNotFoundException('Cette demande n\'existe pas.');
		}

		$entityManager = $doctrine->getManager();
		$entityManager->remove($request);
		$entityManager->flush();

		$this->addFlash(
			'warning',
			'La demande N°'.$id.' a était supprimer.'
		);

		return $this->redirectToRoute('app_request');
	}

	#[Route('/request/edit/{id}', name: 'app_request_edit')]
	#[IsGranted('ROLE_ADMIN')]
	public function edit(int $id, Html_Request $html_request, ManagerRegistry $doctrine): Response
	{
		$entityManager = $doctrine->getManager();
		$request = $doctrine->getRepository(Request::class)->find($id);

		$form = $this->createForm(RequestFormType::class, $request);

		$form->handleRequest($html_request);
		if ($form->isSubmitted() && $form->isValid()) {
			$request = $form->getData();


			$entityManager->persist($request);
			$entityManager->flush();

			$this->addFlash(
				'success',
				'Votre demande a bien était modifié.'
			);

			return $this->redirectToRoute('app_request');
		}

		return $this->render('request/edit.html.twig', [
			'title' => 'Modification de la demande N°'.$id,
			'form' => $form,
		]);
	}



	#[Route('/request/{id}', name: 'app_request_detail')]
	#[IsGranted('ROLE_USER')]
	public function detail(int $id, ManagerRegistry $doctrine): Response
	{
		$request = $doctrine->getRepository(Request::class)->find($id);

		if (!$request) {
			throw $this->createNotFoundException('Cette demande n\'existe pas.');
		}

		return $this->render('request/detail.html.twig', [
			'title' => 'Demande N°'.$id,
			'request' => $request,
		]);
	}
}
