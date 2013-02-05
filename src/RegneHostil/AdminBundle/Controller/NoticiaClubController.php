<?php

namespace RegneHostil\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use RegneHostil\ClubBundle\Entity\Noticia;

class NoticiaClubController extends Controller
{
    
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
			'RegneHostilAdminBundle:NoticiaClub:create.html.twig',
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
			'RegneHostilAdminBundle:NoticiaClub:list.html.twig',
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
					'RegneHostilAdminBundle:NoticiaClub:edit.html.twig',
					array(
						'form' => $form->createView(),
						'updated' => true,
						'id' => $id
					)
				);
			}
		}
		
		return $this->render(
			'RegneHostilAdminBundle:NoticiaClub:edit.html.twig',
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

    	return new RedirectResponse($this->generateUrl('regne_hostil_admin_noticies'));	
	}
}
