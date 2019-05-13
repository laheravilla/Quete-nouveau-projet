<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', ['owner' => 'Thomas',]);
    }
}