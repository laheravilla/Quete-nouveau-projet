<?php


namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'owner' => 'Thomas',
            ]
        );
    }

    /**
     * @Route(
     *     "/article/{slug}",
     *     name = "blog_show",
     *     methods = {"GET"},
     *     requirements = {"slug" = "([a-z0-9]|-)*"})
     */
    public function show($slug = "Article Sans Titre")
    {
        return $this->render('blog/show.html.twig', [
            'title' => ucwords(str_replace('-', ' ', $slug)),
            ]
        );
    }
}