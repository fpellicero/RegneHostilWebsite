<?php

namespace RegneHostil\KingdomsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RegneHostil\KingdomsBundle\Entity\Camp;

class CampsController extends Controller
{
	public function preExecute()
	{
		if($this->get('session')->get('_locale') == NULL) {
			$this->get('session')->set('_locale', 'cat');
		}
		$this->get('request')->setLocale($this->get('session')->get('_locale'));;
		$this->arrayParams['lang'] = $this->get('session')->get('_locale');
	}

	public function listCampsAction()
	{
		$camps = $this->getDoctrine()
			->getRepository('RegneHostilKingdomsBundle:Camp')
			->findByLang('cat');
		
		return $this->render(
			'RegneHostilKingdomsBundle:Camps:list_camps.html.twig',
			array(
				'camps' => $camps
				)
			);
	}

	public function showCampAction($name)
	{
		$campsRepository = $this->getDoctrine()
			->getRepository('RegneHostilKingdomsBundle:Camp');

		$camp  = $campsRepository->findOneBy(array('name' => $name,'lang' => $this->get('session')->get('_locale')));

		$camps = $campsRepository->findByLang('cat');

		return $this->render(
			'RegneHostilKingdomsBundle:Camps:show_camp.html.twig',
			array(
				'camp' => $camp,
				'camps' => $camps
				)
			);
	}

}

?>