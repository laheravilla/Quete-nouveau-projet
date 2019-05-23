<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * Display Welcome message
     *
     * @Route("/", name="app_index")
     */
    public function index()
    {
        return $this->render('blog/default.html.twig', ['greeting' => 'Welcome to my blog',]);
    }

}