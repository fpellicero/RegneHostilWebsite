<?php

namespace RegneHostil\ClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use RegneHostil\ClubBundle\Entity\Noticia;
use RegneHostil\ClubBundle\Entity\Quote;

class DefaultController extends Controller
{
	/**
	 * Controller method for the homepage
	 *
	 */
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
			->getRepository('RegneHostilClubBundle:Noticia');
		$query = $repository->createQueryBuilder('n')
			->setFirstResult( $offset )
			->setMaxResults ( 5 )
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
			'RegneHostilClubBundle:Default:index.html.twig',
			array(
				'noticies' => $noticies,
				'numpage' => $page,
				'newer' => $newer,
				'older' => $older
			)
		);
    }

	/**
	 * Controller method for the about_us page
	 *
	 */
	public function aboutAction()
	{
			return $this->render('RegneHostilClubBundle:Default:about.html.twig');
	}

	/**
	* Controller method for the contact page
	*
	*/
	public function contactAction()
	{
		$form = $this->createFormBuilder()
			->add('name','text')
			->add('email','email')
			->add('text','textarea')
			->getForm();

		/* Generate the captcha script to embed */
		require_once('bundles/regnehostilclub/recaptchalib.php');
		$publickey = "6LeVw8cSAAAAAC1mXZ_IQI-UCUl2XNGy6g3Ex0St";
		$captcha = recaptcha_get_html($publickey);

		return $this->render(
			'RegneHostilClubBundle:Default:contact.html.twig',
			array(
				'form' => $form->createView(),
				'captcha' => $captcha
				)
			);
	}

	/**
	 * Controller method to create Noticies
	 *
	 */
	public function createAction(Request $request)
	{
		$noticia = new Noticia();

		$form = $this->createFormBuilder($noticia)
			->add('title','text')
			->add('date','text')
			->add('text','textarea')
			->getForm();

		if ($request->isMethod('POST')) {
			$form->bind($request);

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($noticia);
				$em->flush();

				return $this->render(
					'RegneHostilClubBundle:Admin:create.html.twig',
					array(
						'form' => $form->createView(),
						'created' => true
					)
				);
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
}
