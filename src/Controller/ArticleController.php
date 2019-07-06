<?php
namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @param ArticleRepository $articleRepository
     * @Route("/", name="article_index", methods={"GET"}    )
     * @return Response
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAllWithCategoriesAndTags(),
        ]);
    }
    /**
     * @param Request $request
     * @param Slugify $slugify
     * @param \Swift_Mailer $mailer
     * @param Security $security
     * @Route("/new", name="article_new", methods={"GET","POST"})
     * @return Response
     */
    public function new(Request $request, Slugify $slugify, \Swift_Mailer $mailer, Security $security): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($article->getTitle());
            $article->setSlug($slug);

            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            if ($security->getUser()) {
                $author = $security->getUser();
                $article->setAuthor($author);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $message = (new \Swift_Message('A new article has been published!'))
                ->setFrom($this->getParameter('mailer_from'))
                ->setTo($this->getParameter('mailer_to'))
                ->setBody(
                    $this->render('article/email/notification.html.twig', [
                        'article' => $article
                    ]),
                    'text/html'
                );

            $mailer->send($message);


            return $this->redirectToRoute('article_index');
        }
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param Article $article
     * @Route("/{id}", name="article_show", methods={"GET"})
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
    /**
     * @param Request $request
     * @param Article $article
     * @param Slugify $slugify
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     * @return Response
     */
    public function edit(Request $request, Article $article, Slugify $slugify, Security $security): Response
    {
        if ($security->getUser() === $article->getAuthor() && $security->isGranted('ROLE_AUTHOR')) {

            $form = $this->createForm(ArticleType::class, $article);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
                $slug = $slugify->generate($article->getTitle());
                $article->setSlug($slug);

                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('article_index', [
                    'id' => $article->getId(),
                ]);
            }
        } else {
            $this->denyAccessUnlessGranted('EDIT', $article, 'You are not the author of this article!');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request, Article $article
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     * @return Response
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('article_index');
    }
}