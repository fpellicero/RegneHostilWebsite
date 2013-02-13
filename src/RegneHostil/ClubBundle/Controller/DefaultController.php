<?php

namespace RegneHostil\ClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


use RegneHostil\ClubBundle\Entity\Noticia;
use RegneHostil\ClubBundle\Entity\Quote;

class DefaultController extends Controller
{
	private $arrayParams;
	private $lang;

	private function getRandomQuote() {
		$quotes = $this->getDoctrine()
			->getRepository('RegneHostilClubBundle:Quote')
			->findAll();

		$quote = array_rand($quotes,1);
		return nl2br($quotes[$quote]->getQuote());
	}

	public function preExecute()
	{
		$session = $this->get('session');

		if($session->get('_locale') == NULL) {
			$session->set('_locale', 'cat');
		}

		$this->get('request')->setLocale($session->get('_locale'));;
		$this->arrayParams['lang'] = $session->get('_locale');
		$this->arrayParams['quote'] = $this->getRandomQuote();
	}

	public function changeLanguageAction($lang)
	{
		$this->get('session')->set('_locale', $lang);
		return new RedirectResponse($_SERVER['HTTP_REFERER']);
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
			->setParameter('lang', $this->arrayParams['lang'])
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

		$this->arrayParams['noticies'] = $noticies;
		$this->arrayParams['numpage'] = $page;
		$this->arrayParams['newer'] = $newer;
		$this->arrayParams['older'] = $older;

		return $this->render(
			'RegneHostilClubBundle:Default:index.html.twig',
			$this->arrayParams
		);
    }

	/**
	 * Controller method for the about_us page
	 *
	 */
	public function aboutAction()
	{
			return $this->render(
				'RegneHostilClubBundle:Default:about.html.twig',
				$this->arrayParams
				);
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
		$this->arrayParams['form'] = $form->createView();
		$this->arrayParams['captcha'] = $captcha;
		$this->arrayParams['error'] = $error;
		$this->arrayParams['success'] = $success;
		$this->arrayParams['successMessage'] = $successMessage;
		$this->arrayParams['errorMessage'] = $errorMessage;
		return $this->render(
			'RegneHostilClubBundle:Default:contact.html.twig',
			$this->arrayParams
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

	public function galleryIndexAction()
	{
			// Si no hem triat un album
			$albums = scandir($_SERVER['DOCUMENT_ROOT'] . "/gallery/");	// Obtenim tots els directoris de la galeria
			unset($albums[0]);														// Fem unset dels directoris ./ i ../
			unset($albums[1]);
			// Li passem el nom dels albums a Smarty i indiquem que no tenim seleccionat cap album
			$this->arrayParams['albums'] = $albums;

			return $this->render(
				'RegneHostilClubBundle:Default:gallery_index.html.twig',
				$this->arrayParams
				);
	}

	public function showAlbumAction($album)
	{
		$photos = scandir($_SERVER['DOCUMENT_ROOT'] ."/gallery/" . $album);
		unset($photos[0]); unset($photos[1]);

		$this->arrayParams['album'] = $album;
		$this->arrayParams['photos'] = $photos;
		
		return $this->render(
			'RegneHostilClubBundle:Default:gallery_show_album.html.twig',
			$this->arrayParams
			);
	}

	public function showNoticiaAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$noticia = $em->getRepository('RegneHostilClubBundle:Noticia')->find($id);

		$this->arrayParams['noticia'] = $noticia;

		return $this->render(
			'RegneHostilClubBundle:Default:show_noticia.html.twig',
			$this->arrayParams
			);
	}
	
}

?>
