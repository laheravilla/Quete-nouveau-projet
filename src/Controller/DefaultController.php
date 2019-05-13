<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/app_index")
     */
    public function index()
    {
        return $this->render('blog/default.html.twig', ['greeting' => 'Bienvenue sur mon blog',]);
    }

}