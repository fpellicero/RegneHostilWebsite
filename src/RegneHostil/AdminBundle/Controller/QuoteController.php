<?php

namespace RegneHostil\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use RegneHostil\ClubBundle\Entity\Quote;

class QuoteController extends Controller
{
    public function listQuotesAction() {
		$quotes = $this->getDoctrine()
			->getRepository('RegneHostilClubBundle:Quote')
			->findAll();
		
		return $this->render(
			'RegneHostilAdminBundle:Quote:list_quotes.html.twig',
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
					'RegneHostilAdminBundle:Quote:edit_quote.html.twig',
					array(
						'form' => $form->createView(),
						'updated' => true,
						'id' => $id
					)
				);
			}
		}
		
		return $this->render(
			'RegneHostilAdminBundle:Quote:edit_quote.html.twig',
			array(
					'form' => $form->createView(),
					'updated' => false,
					'id' => $id
			)
		);
	}

	public function deleteQuoteAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
    	$quote = $em->getRepository('RegneHostilClubBundle:Quote')->find($id);

    	$em->remove($quote);
    	$em->flush();

    	return new RedirectResponse($this->generateUrl('regne_hostil_admin_quotes'));	
	}

	public function createQuoteAction(Request $request)
	{
		$quote = new Quote();

		$form = $this->createFormBuilder($quote)
			->add('quote','textarea')
			->add('valid','integer')
			->getForm();

		if ($request->isMethod('POST')) {
			$form->bind($request);

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($quote);
				$em->flush();

				return new RedirectResponse($this->generateUrl('regne_hostil_admin_quotes')); 
			}
		}
		return $this->render(
			'RegneHostilAdminBundle:Quote:create_quote.html.twig',
			array(
					'form' => $form->createView(),
					'created' => false
			)
		);
	}
}
