<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\Article;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $repoArticle = $em->getRepository(Article::class);
      $articles = $repoArticle->derniersArticles(4);

      // return new Response(var_dump($articles));
      return $this->render('base.html.twig', [
          'articles' => $articles,
      ]);

    }

    public function lireArticleAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $article = $em->getRepository(Article::class)->find($id);

      return $this->render('AppBundle:Default:lire-article.html.twig', [
        'article' => $article
      ]);
    }

    public function tousLesArticlesAction(Request $request)
    {
      $em = $this->getDoctrine();
      $repoArticle = $em->getRepository(Article::class);
      $articles = $repoArticle->findNoneArchived();


      return $this->render('AppBundle:Default:tous-les-articles.html.twig', [
        'articles' => $articles
      ]);
    }

}
