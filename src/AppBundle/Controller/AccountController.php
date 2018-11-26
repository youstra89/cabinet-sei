<?php
// src/Controller/LuckyController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Rdv;
use AppBundle\Entity\Message;
use AppBundle\Repositiry\MessageRepositiry;

class AccountController extends Controller
{
  public function indexAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $repoRdv = $em->getRepository('AppBundle:Rdv');
    $repoMessage = $em->getRepository('AppBundle:Message');

    if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
    // if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
      $request->getSession()->getFlashBag()->add('warning', 'Connectez vous pour vAppter cette page');
      return $this->redirectToRoute('fos_user_security_login');
    }
    $userId = $this->get('security.token_storage')->getToken()->getUser()->getId();

    $rdv = $repoRdv->findBy(['user' => $userId]);
    $messages = $repoMessage->findBy(['user' => $userId]);

    return $this->render('AppBundle:Account:index.html.twig', [
      "rdv" => $rdv,
      "messages" => $messages
    ]);
  }

  public function updateProfileAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
    // if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
      $request->getSession()->getFlashBag()->add('warning', 'Connectez vous pour vAppter cette page');
      return $this->redirectToRoute('fos_user_security_login');
    }

    if($request->isMethod('post'))
    {
      $data = $request->request->all();
      $nom = $data['nom'];
      $pnom = $data['pnom'];
      $contact = $data['contact'];
      $filiere = $data['filiere'];
      $level = $data['level'];
      $school = $data['school'];
      if(empty($nom) || empty($pnom))
      {
        $request->getSession()->getFlashBag()->add('error', 'Votre nom ou prénom ne peut être vide.');
        return $this->redirectToRoute('update_profile');
      }
      if(empty($contact))
      {
        $request->getSession()->getFlashBag()->add('error', 'Le contact ne peut être vide.');
        return $this->redirectToRoute('update_profile');
      }
      if(empty($level) || empty($school))
      {
        $request->getSession()->getFlashBag()->add('error', 'Vérifiez le niveau et l\'école saisies.');
        return $this->redirectToRoute('update_profile');
      }

      $user = $this->getUser();
      $user->setNom($nom);
      $user->setPnom($pnom);
      $user->setContact($contact);
      $user->setFiliere($filiere);
      $user->setLevel($level);
      $user->setSchool($school);
      $em->flush();
      $request->getSession()->getFlashBag()->add('success', 'Votre profil a été mis à jour avec succès.');
      return $this->redirectToRoute('my_account');
    }
    return $this->render('AppBundle:Account:update-profile.html.twig', [
    ]);
  }
}
