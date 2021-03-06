<?php

namespace RegneHostil\KingdomsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RegneHostil\KingdomsBundle\Entity\Chronicle;

class ChroniclesController extends Controller
{

	public function preExecute()
	{
		if($this->get('session')->get('_locale') == NULL) {
			$this->get('session')->set('_locale', 'cat');
		}
		$this->get('request')->setLocale($this->get('session')->get('_locale'));;
		$this->arrayParams['lang'] = $this->get('session')->get('_locale');
	}

	public function listChroniclesAction()
	{
		$repository = $this->getDoctrine()->getRepository('RegneHostilKingdomsBundle:Chronicle');

		$chronicles2011 = $repository->findBy(
			array(
				'lang' => $this->get('session')->get('_locale'),
				'year' => '2011'
				)
			);

		$chronicles2012 = $repository->findBy(
			array(
				'lang' => $this->get('session')->get('_locale'),
				'year' => '2012'
				)
			);

		return $this->render(
			'RegneHostilKingdomsBundle:Chronicles:list_chronicles.html.twig',
			array(
				'chronicles2011' => $chronicles2011,
				'chronicles2012' => $chronicles2012
				)
			);
	}

	public function showChronicleAction($chapter)
	{
		$repository = $this->getDoctrine()->getRepository('RegneHostilKingdomsBundle:Chronicle'); 
		
		$chronicle = $repository->findOneBy(array('chapter' => $chapter, 'lang' => $this->get('session')->get('_locale') ));

		$next = false;
		if ($repository->findOneBy(array('chapter' => $chapter + 1, 'lang' => $this->get('session')->get('_locale') ))) {
			$next = true;
		}

		$prev = false;
		if ($repository->findOneBy(array('chapter' => $chapter - 1, 'lang' => $this->get('session')->get('_locale') ))) {
			$prev = true;
		}

		return $this->render(
			'RegneHostilKingdomsBundle:Chronicles:show_chronicle.html.twig',
			array(
				'chronicle' => $chronicle,
				'chapter' => $chapter,
				'next' => $next,
				'prev' => $prev
				)
			);
	}
}