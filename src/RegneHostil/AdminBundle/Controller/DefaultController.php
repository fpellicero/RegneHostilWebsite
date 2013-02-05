<?php

namespace RegneHostil\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RegneHostilAdminBundle::layout.html.twig');
    }
}
