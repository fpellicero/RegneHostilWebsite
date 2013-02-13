<?php

namespace RegneHostil\KingdomsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RegneHostil\KingdomsBundle\Entity\Camp;

class CampsController extends Controller
{
	public function listCampsAction()
	{
		$camps = $this->getDoctrine()
			->getRepository('RegneHostilKingdomsBundle:Camp')
			->findAll();
		
		return $this->render(
			'RegneHostilKingdomsBundle:Camps:list_camps.html.twig',
			array(
				'camps' => $camps
				)
			);
	}

	public function showCampAction($name)
	{
		$camp = $this->getDoctrine()
			->getRepository('RegneHostilKingdomsBundle:Camp')
			->findOneByName($name);

		return $this->render(
			'RegneHostilKingdomsBundle:Camps:show_camp.html.twig',
			array(
				'camp' => $camp
				)
			);
	}

}

?>