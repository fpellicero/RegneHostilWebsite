<?php

namespace RegneHostil\ClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use RegneHostil\ClubBundle\Entity\Noticia;
use RegneHostil\ClubBundle\Entity\Quote;

class AdminController extends Controller
{
	public function indexAction() {
		return $this->render(
			'RegneHostilClubBundle:Admin:layout.html.twig'
			);
	}

	public function createNoticiaAction(Request $request)
	{
		$noticia = new Noticia();

		$form = $this->createFormBuilder($noticia)
			->add('title','text')
			->add('lang','text')
			->add('date','date')
			->add('text','textarea')
			->getForm();

		if ($request->isMethod('POST')) {
			$form->bind($request);

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($noticia);
				$em->flush();

				return new RedirectResponse($this->generateUrl('regne_hostil_club_admin_noticies')); 
			}
		}
		return $this->render(
			'RegneHostilClubBundle:Admin:create.html.twig',
			array(
					'form' => $form->createView(),
					'created' => false
			)
		);
	}

	public function llistarNoticiesAction() {
		$noticies = $this->getDoctrine()
			->getRepository('RegneHostilClubBundle:Noticia')
			->findAll();
		
		return $this->render(
			'RegneHostilClubBundle:Admin:list_noticies.html.twig',
			array(
				'noticies' => $noticies
				)
			);
	}

	public function editNoticiaAction(Request $request,$id) {
		
		$em = $this->getDoctrine()->getManager();
    	$noticia = $em->getRepository('RegneHostilClubBundle:Noticia')->find($id);

		$form = $this->createFormBuilder($noticia)
			->add('title','text')
			->add('date','date')
			->add('text','textarea')
			->getForm();
		
		if ($request->isMethod('POST')) {
			$form->bind($request);

			if($form->isValid()) {
				$em->flush();

				return $this->render(
					'RegneHostilClubBundle:Admin:edit_noticia.html.twig',
					array(
						'form' => $form->createView(),
						'updated' => true,
						'id' => $id
					)
				);
			}
		}
		
		return $this->render(
			'RegneHostilClubBundle:Admin:edit_noticia.html.twig',
			array(
					'form' => $form->createView(),
					'updated' => false,
					'id' => $id
			)
		);
	}

	public function deleteNoticiaAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
    	$noticia = $em->getRepository('RegneHostilClubBundle:Noticia')->find($id);

    	$em->remove($noticia);
    	$em->flush();

    	return new RedirectResponse($this->generateUrl('regne_hostil_club_admin_noticies'));	
	}

	public function listQuotesAction() {
		$quotes = $this->getDoctrine()
			->getRepository('RegneHostilClubBundle:Quote')
			->findAll();
		
		return $this->render(
			'RegneHostilClubBundle:Admin:list_quotes.html.twig',
			array(
				'quotes' => $quotes
				)
			);
	}

	public function editQuoteAction(Request $request, $id) {
		$em = $this->getDoctrine()->getManager();
    	$quote = $em->getRepository('RegneHostilClubBundle:Quote')->find($id);

		$form = $this->createFormBuilder($quote)
			->add('quote','textarea')
			->add('valid','integer')
			->getForm();
		
		if ($request->isMethod('POST')) {
			$form->bind($request);

			if($form->isValid()) {
				$em->flush();

				return $this->render(
					'RegneHostilClubBundle:Admin:edit_noticia.html.twig',
					array(
						'form' => $form->createView(),
						'updated' => true,
						'id' => $id
					)
				);
			}
		}
		
		return $this->render(
			'RegneHostilClubBundle:Admin:edit_quote.html.twig',
			array(
					'form' => $form->createView(),
					'updated' => false,
					'id' => $id
			)
		);
	}

}