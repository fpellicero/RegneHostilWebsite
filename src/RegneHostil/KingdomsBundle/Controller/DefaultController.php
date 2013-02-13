<?php

namespace RegneHostil\KingdomsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use RegneHostil\KingdomsBundle\Entity\Noticia;

class DefaultController extends Controller
{
	public function preExecute()
	{
		if($this->get('session')->get('_locale') == NULL) {
			$this->get('session')->set('_locale', 'cat');
		}
		$this->get('request')->setLocale($this->get('session')->get('_locale'));;
		$this->arrayParams['lang'] = $this->get('session')->get('_locale');
	}


    public function indexAction()
    {
    	// We set the offset based on the page parameter
		$page = 0;
		if(isset($_GET['page'])) {
			$page = $_GET['page'];
		}
		$offset = $page * 5;

		// Building the query to fetch the objects from db
		$repository = $this->getDoctrine()
			->getRepository('RegneHostilKingdomsBundle:Noticia');
		$query = $repository->createQueryBuilder('n')
			->where('n.lang = :lang')
			->setParameter('lang', $this->get('session')->get('_locale'))
			->setFirstResult( $offset )
			->setMaxResults ( 5 )
			->orderBy('n.date', 'DESC')
			->getQuery();

		// Fetch the objects
		$noticies = $query->getResult();

		// Decide wether to put the 'older' and 'newer' button or not
		$older = true;
		if(sizeof($noticies) < 5) {
			$older = false;	
		}
		$newer = true;
		if($offset == 0) {
			$newer = false;
		}

		return $this->render(
			'RegneHostilKingdomsBundle:Default:index.html.twig',
			array(
				'noticies' => $noticies,
				'numpage' => $page,
				'newer' => $newer,
				'older' => $older
				)
		);

        return $this->render('RegneHostilKingdomsBundle:Default:index.html.twig');
    }

    public function infoAction()
    {
    	return $this->render('RegneHostilKingdomsBundle:Default:info.html.twig');
    }

    public function concursLiterariAction()
    {
    	return $this->render('RegneHostilKingdomsBundle:Default:concurs_literari.html.twig');
    }

    public function campsAction()
    {
    	return $this->render('RegneHostilKingdomsBundle:Default:camps.html.twig');
    }
}
