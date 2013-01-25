<?php

namespace RegneHostil\ClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use RegneHostil\ClubBundle\Entity\Noticia;
use RegneHostil\ClubBundle\Entity\Quote;

class DefaultController extends Controller
{
	private $quote;

	private function getRandomQuote() {
		$quotes = $this->getDoctrine()
			->getRepository('RegneHostilClubBundle:Quote')
			->findAll();

		$quote = array_rand($quotes,1);
		return nl2br($quotes[$quote]->getQuote());
	}

	public function preExecute()
	{
		$this->quote = $this->getRandomQuote();
	}

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
			->where('n.lang = :lang')
			->setParameter('lang', 'cat')
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
			'RegneHostilClubBundle:Default:index.html.twig',
			array(
				'noticies' => $noticies,
				'numpage' => $page,
				'newer' => $newer,
				'older' => $older,
				'quote' => $this->quote
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
	public function contactAction(Request $request)
	{
		$success = false;
		$error = false;
		$successMessage = "";
		$errorMessage = "";
		/* Generate the captcha script to embed */
		require_once('bundles/regnehostilclub/recaptchalib.php');
		$publickey = "6LeVw8cSAAAAAC1mXZ_IQI-UCUl2XNGy6g3Ex0St";
		$captcha = recaptcha_get_html($publickey);
		
		$form = $this->createFormBuilder()
			->add('name','text')
			->add('email','email')
			->add('text','textarea')
			->getForm();

		/* Check if it's a form submission */
		if($request->isMethod('POST')) {
			/* Check the captcha answer */
			require_once('bundles/regnehostilclub/recaptchalib.php');
			$privatekey = "6LeVw8cSAAAAAMhcG1pEZaOXmWdry8o_U4qqWp2a";
			$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

			if (!$resp->is_valid) {
				// Captcha incorrecte, tornem enrere i no fem res.
				$error = true;
				$errorMessage = "Bad captcha solution";
			} else {
				// Captcha correcte, seguim endavant i enviem el mail.
				$data = $form->getData();
				
				$to = 'pelly.obn91@gmail.com';
				$subject = 'Formulari de Contacte';
				$text = 'Nom: ' . $data['name'] . '\nE-mail: ' . $data['email'] . '\n\n' . $data['text'];
				
				$sended = mail($to,$subject,$text);
				if($sended) {
					$success = true;
					$successMessage = "La teva consulta s'ha enviat correctament! Ens posarem en contacte amb tu tant aviat com sigui possible.";
				}else {
					$error = true;
					$errorMessage = "La teva consulta s'ha enviat correctament! Ens posarem en contacte amb tu tant aviat com sigui possible.";
				}
			}
		}

		/* Render the form */
		return $this->render(
			'RegneHostilClubBundle:Default:contact.html.twig',
			array(
				'form' => $form->createView(),
				'captcha' => $captcha,
				'error' => $error,
				'success' => $success,
				'successMessage' => $successMessage,
				'errorMessage' => $errorMessage
				)
			);
	}

	public function createQuoteAction(Request $request) {
		$quote = new Quote();

		$quote->setValid(0);

		$form = $this->createFormBuilder($quote)
			->add('quote','textarea')
			->getForm();

		if ($request->isMethod('POST')) {
			$form->bind($request);

			if($form->isValid()) {
				$em = $this->getDoctrine()->getManager();
				$em->persist($noticia);
				$em->flush();

				return new RedirectResponse($this->generateUrl('regne_hostil_club_homepage')); 
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

	/**
	 * Controller method to create Noticies
	 *
	 */
	
}
