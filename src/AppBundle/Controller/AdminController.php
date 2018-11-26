<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

use Doctrine\ORM\Tools\Pagination\Paginator;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use AppBundle\Form\ArticleType;
use UserBundle\Entity\User;
use AppBundle\Entity\Rdv;
use AppBundle\Entity\Article;
use AppBundle\Entity\Message;

class AdminController extends Controller
{
  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $repoRdv = $em->getRepository('AppBundle:Rdv');
    $repoMessage = $em->getRepository('AppBundle:Message');
    $repoArticle = $em->getRepository('AppBundle:Article');

    $userManager = $this->get('fos_user.user_manager');
    // $userManager = $container->get('fos_user.user_manager');
    $users = $userManager->findUsers();
    $rdv   = $repoRdv->findAll();
    $messages = $repoMessage->findByReadAt();
    $articles = $repoArticle->findBy(['publited' => TRUE, 'archived' => FALSE]);
    // if (false === $authChecker->isGranted('ROLE_ADMIN')) {
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:index.html.twig', [
      'msgNonLus' => count($messages),
      'nbrUsers'  => count($users),
      'nbrRdv'    => count($rdv),
      'articles'  => count($articles),
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function messagesAction(Request $request)
  {
    $em = $this->getDoctrine();
    $repoMessage = $em->getRepository(Message::class);

    /**
     * @var $paginator \Knp\Component\Pager\Paginator
     */
    $paginator = $this->get('knp_paginator');

    $query = $repoMessage->myFindAll();
    $messages = $paginator->paginate(
        $query,
        $request->query->get('page', 1),
        $request->query->get('limit', 10)

    );
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:messages.html.twig', [
      'messages' => $messages
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function membresAction(Request $request)
  {
    $em = $this->getDoctrine();
    $repoUser = $em->getRepository(User::class);
    $users    = $repoUser->findAll();
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:membres.html.twig', [
      'users'  => $users
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function profileAction(Request $request, $id)
  {
    $em = $this->getDoctrine();
    $repoUser    = $em->getRepository(User::class);
    $repoRdv     = $em->getRepository('AppBundle:Rdv');
    $repoMessage = $em->getRepository('AppBundle:Message');
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    $user     = $repoUser->find($id);
    $rdv      = $repoRdv->findBy(['user' => $id]);
    $messages = $repoMessage->findBy(['user' => $id]);
    return $this->render('AppBundle:Admin:user-profile.html.twig', [
      'rdv'  => $rdv,
      'user'  => $user,
      'messages'  => $messages,
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function lireMessageAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();
    $repoMessage = $em->getRepository(Message::class);
    $message = $repoMessage->find($id);
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    $message->setReadAt(new \Datetime());
    $em->flush();
    return $this->render('AppBundle:Admin:lire-message.html.twig', [
      'message' => $message
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function tousLesRdvAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $repoRdv = $em->getRepository("AppBundle:Rdv");
    $rdv = $repoRdv->findAll();
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }

    foreach ($rdv as $key => $r) {
      $date[$key]  = $r->getDateRdv();
      $heure[$key] = $r->getHeure();
    }
    array_multisort($date, SORT_ASC, $heure, SORT_ASC, $rdv);

    return $this->render('AppBundle:Admin:rdv.html.twig', [
      'rdv' => $rdv
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function voirRdvAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();
    $repoRdv = $em->getRepository("AppBundle:Rdv");
    $rdv = $repoRdv->find($id);
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }

    if($request->isMethod('post'))
    {
      $data = $request->request->all();
      $heureArrivee = $data['heureArrivee'];
      $commentaire  = $data['commentaire'];
      if(empty($heureArrivee) || empty($commentaire))
      {
        $request->getSession()->getFlashBag()->add('error', 'Le formualire n\'est pas complet. Il manque l\'heure ou le commentaire.');
        return $this->redirectToRoute('voir_rdv', ['id' => $id]);
      }

      $rdv->setHeureArrivee($heureArrivee);
      $rdv->setCommentaire($commentaire);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', 'Les informations sur le rendez-vous ont été enregistrées avec succès.');
      return $this->redirectToRoute('tous_les_rdv');
    }

    return $this->render('AppBundle:Admin:voir-rdv.html.twig', [
      'rdv' => $rdv
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function articlesAction(Request $request)
  {
    $em = $this->getDoctrine();
    $repoArticle = $em->getRepository(Article::class);
    $articles = $repoArticle->findNoneArchived();
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:articles.html.twig', [
      'articles' => $articles
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function redigerArticleAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);

    if($form->handleRequest($request)->isValid())
  	{
      $data = $request->request->all();
      $text = $data['editor1'];
      if(empty($text))
      {
        $request->getSession()->getFlashBag()->add('error', 'Le contenu de l\'aricle ne doit pas être vide.');
        return $this->redirect($this->generateUrl('rediger_article'));
      }
      // $text = $request->request->get('editor1');
      // return new Response(var_dump($text));
      /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        if (!is_null($article->getImg())) {
          # code...
          $file = $article->getImg();
          // return new Response(var_dump($file->guessExtension()));
          if($file->guessExtension() != 'jpeg')
          {
            $request->getSession()->getFlashBag()->add('error', 'Vous ne pouvez envoyer que les images au format "JPG".');
            return $this->redirect($this->generateUrl('rediger_article'));
          }

          $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

          // moves the file to the directory where brochures are stored
          $file->move(
              $this->getParameter('support_directory'),
              $fileName
          );

          // updates the 'brochure' property to store the PDF file name
          // instead of its contents
          $article->setNomFichier($file->getClientOriginalName());
          $article->setImg($fileName);
        }
        $userManager = $this->get('fos_user.user_manager');
        $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $user = $userManager->findUserBy(['id' => $userId]);
        $article->setUser($user);
        $article->setContent($text);
        $article->setDateSave(new \Datetime());
        $article->setDateUpdate(new \Datetime());
        if($article->getPublited() == TRUE)
          $article->setDatePublished(new \Datetime());

        $em->persist($article);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Votre article a bien été créer.');
        return $this->redirect($this->generateUrl('articles'));

    }

    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:rediger-article.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @return string
   */
  private function generateUniqueFileName()
  {
      // md5() reduces the similarity of the file names generated by
      // uniqid(), which is based on timestamps
      return md5(uniqid());
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function editerArticleAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();
    $article = $em->getRepository(Article::class)->find($id);
    $form = $this->createForm(ArticleType::class, $article);

    if($form->handleRequest($request)->isValid())
  	{
      $data = $request->request->all();
      $text = $data['editor1'];
      if(empty($text))
      {
        $request->getSession()->getFlashBag()->add('error', 'Le contenu de l\'aricle ne doit pas être vide.');
        return $this->redirect($this->generateUrl('editer_article', ['id' => $id]));
      }
      $article->setDateUpdate(new \Datetime());
      if($article->getPublited() == TRUE)
      {
        $article->setDatePublished(new \Datetime());
      }
      else{
        $article->setDatePublished(NULL);
      }

      $article->setContent($text);
      $em->persist($article);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', 'Votre article a été édité avec succès.');
      return $this->redirect($this->generateUrl('articles'));
    }

    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:editer-article.html.twig', [
      'form'    => $form->createView(),
      'article' => $article
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function voirArticleAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();
    $article = $em->getRepository(Article::class)->find($id);

    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }
    return $this->render('AppBundle:Admin:voir-article.html.twig', [
      'article' => $article
    ]);
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function archiverArticleAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
      throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }

    $article = $em->getRepository(Article::class)->find($id);
    if($this->isCsrfTokenValid('archiver'.$article->getId(), $request->get('_token')))
    {
      $article->setArchived(TRUE);
      $article->setDateUpdate(new \Datetime());
      $em->flush();

      $request->getSession()->getFlashBag()->add('success', 'Votre article a été archivé avec succès.');
      return $this->redirect($this->generateUrl('articles'));
    }
  }

  /**
  * @Security("has_role('ROLE_ADMIN')")
  */
  public function archiveAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
      throw new AccessDeniedException('Vous n\'avez pas droit à cette ressources!');
    }

    $articles = $em->getRepository(Article::class)->findBy(['archived' => TRUE]);
    return $this->render('AppBundle:Admin:archive-articles.html.twig', [
      'articles' => $articles
    ]);
  }
}
