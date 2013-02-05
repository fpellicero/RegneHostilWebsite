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

}