<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @param ArticleRepository $repos
     *
     * Show all row from articles's entity
     *
     * @Route("/blog", name="blog_index")
     *
     * @return Response
     */
    public function index(ArticleRepository $repos): Response
    {
        $articles = $repos->findAll();

        if (!$articles) {
            throw $this->createNotFoundException("No article found in article's table.");
        }

        return $this->render('blog/index.html.twig', ['articles' => $articles]);
    }

    /**
     * Display one article by slug
     *
     * @param string $slug The slugger
     *
     * @Route(
     *     "/blog/{slug}",
     *     defaults={"slug" = null},
     *     name = "blog_show",
     *     methods = {"GET"},
     *     requirements = {"slug" = "([a-z0-9]|-)*"})
     *
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
            ->createNotFoundException("No slug has been sent to find an article's table");
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), '-')
        );

        $reposArticles = $this->getDoctrine()->getRepository(Article::class);
        $article = $reposArticles->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException("No article with '.$slug.' title found in article's table");
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
                'slug' => $slug,
            ]
        );
    }

//    /**
//     * Display 3 articles by category
//     *
//     * @param string $category
//     *
//     * @Route("/blog/category/{category}",
//     *     defaults={"category" = "Javascript"},
//     *     name = "show_category",
//     *     methods = {"GET"},)
//     *
//     * @return Response
//     */
//    public function showByCategory(string $category): Response
//    {
//        $reposCategory = $this->getDoctrine()->getRepository(Category::class);
//        $category = $reposCategory->findOneBy(['name' => ucfirst($category)]);
//
//        if (!$category) {
//            throw $this->createNotFoundException("No category found.");
//        }
//
//        $reposArticles = $this->getDoctrine()->getRepository(Article::class);
//        $articles = $reposArticles->findBy(['category' => $category], ['id' => 'DESC'], 3);
//
//        if (!$articles) {
//            throw $this->createNotFoundException("No articles found.");
//        }
//
//        return $this->render('blog/category.html.twig', [
//            'category' => $category,
//                'articles' => $articles,
//            ]
//        );
//    }

    /**
     * @param Category $category, CategoryRepository $categoryRepo
     *
     * @Route("/blog/category/{name}", name = "show_category", methods = {"GET"})
     *
     * @return Response
     */
    public function showByCategory(Category $category): Response
    {
        return $this->render('blog/category.html.twig', [
                'category' =>  $category,
                'articles' => $category->getArticles(),
            ]
        );
    }

    /**
     * @param Tag $tag
     *
     * @Route("/blog/tag/{name}", name = "show_tag", methods = {"GET"})
     *
     * @return Response
     */
    public function showByTag(Tag $tag): Response
    {
        return $this->render('blog/tag.html.twig', [
                'tag' =>  $tag,
                'articles' => $tag->getArticles(),
            ]
        );
    }


}
