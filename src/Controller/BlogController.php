<?php
namespace App\Controller;
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
     * Show all row from articles's entity
     * @Route("/blog", name="blog_index")
     * @return Response
     */
    public function index(ArticleRepository $repos): Response
    {
        $articles = $repos->findAllWithCategoriesAndTags();
        if (!$articles) {
            throw $this->createNotFoundException("No article found in article's table.");
        }
        return $this->render('blog/index.html.twig', [
                'articles' => $articles,
            ]
        );
    }
    /**
     * @param $title
     * @param ArticleRepository $articleRepository
     * @Route(
     *     "/blog/{title}",
     *     name = "blog_show",
     *     methods = {"GET"})
     * @return Response
     */
    public function show(?string $title, ArticleRepository $articleRepository): Response
    {
        if (!$title) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }
        $article = $articleRepository->findOneBy(['title' => mb_strtolower($title)]);
        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$title.' title, found in article\'s table.'
            );
        }
        return $this->render(
            'blog/show.html.twig',
            [
                'article' => $article,
                'title' => $title,
            ]
        );
    }
    /**
     * @param Category $category, CategoryRepository $categoryRepo
     * @Route("/blog/category/{name}", name = "show_category", methods = {"GET"})
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
     * @Route("/blog/tag/{name}", name = "show_tag", methods = {"GET"})
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