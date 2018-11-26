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

class ContactController extends Controller
{
    public function indexAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
        $user = $this->getUser();
        if(empty($user->getNom()) || empty($user->getPnom()))
        {
          $request->getSession()->getFlashBag()->add('warning', 'Veuillez complèter votre profile avant de contituner.');
          return $this->redirectToRoute('update_profile');
        }
      }

      if($request->isMethod('post')){
        $nom     = $request->request->get('nom');
        $email   = $request->request->get('email');
        $contact = $request->request->get('contact');
        $subject = $request->request->get('subject');
        $content = $request->request->get('message');
        // return new Response(var_dump($nom));
        $message = new Message();
        if (!empty($this->getUser())) {
          $message->setUser($this->get('security.token_storage')->getToken()->getUser()->getId());
        }
        $message->setFullname($nom);
        $message->setEmail($email);
        $message->setContact($contact);
        $message->setSubject($subject);
        $message->setMessage($content);
        $message->setDateSave(new \Datetime());
        $message->setDateUpdate(new \Datetime());

        // return new Response(var_dump($message));
        $em->persist($message);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'Votre message a bien été délivré.');
        return $this->redirectToRoute('contact_us');
      }

      return $this->render('AppBundle:Contact:index.html.twig', [
      ]);
    }

    public function rdvAction(Request $request)
    {
      if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      // if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
        $request->getSession()->getFlashBag()->add('warning', 'Veuillez vous inscrire d\'abord avant de fixer un rendez-vous');
        return $this->redirectToRoute('fos_user_security_login');
      }

      $em = $this->getDoctrine()->getManager();

      $user = $this->getUser();
      if(empty($user->getNom()) || empty($user->getPnom()))
      {
        $request->getSession()->getFlashBag()->add('warning', 'Veuillez complèter votre profile avant de contituner.');
        return $this->redirectToRoute('update_profile');
      }

      if($request->isMethod('post'))
      {
        $data = $request->request->all();
        $nom     = $data["nom"];
        $date    = $data["date"];
        $heure   = $data["heure"];
        $email   = $data["email"];
        $subject = $data["subject"];
        $message = $data["message"];

        $rdvUser = $em->getRepository(Rdv::class)->findBy(["user" => $user->getId(), "tenue_rdv" => FALSE]);

        if(!empty($rdvUser))
        {
          $request->getSession()->getFlashBag()->add('error', 'Vous avez déjà un rendez-vous qui ne s\'est pas encore tenue.');
          return $this->redirectToRoute('my_account');
        }

        if(empty($date))
        {
          $request->getSession()->getFlashBag()->add('error', 'Veuillez choisir une date pour fixer votre rendez-vous!');
          return $this->redirectToRoute('rdv');
        }

        $good_format = strtotime ($date);
        if(date('N',$good_format) == 6 || date('N',$good_format) == 7)
        {
          $request->getSession()->getFlashBag()->add('error', 'Sélectionnez un jour ouvrable!');
          return $this->redirectToRoute('rdv');
        }
        if(empty($heure))
        {
          $request->getSession()->getFlashBag()->add('error', 'Veuillez choAppr une heure pour fixer votre rendez-vous!');
          return $this->redirectToRoute('rdv');
        }
        if(empty($subject))
        {
          $request->getSession()->getFlashBag()->add('error', 'Le sujet de votre message ne doit pas être vide');
          return $this->redirectToRoute('rdv');
        }
        if(empty($message))
        {
          $request->getSession()->getFlashBag()->add('error', 'Le message ne doit pas être vide');
          return $this->redirectToRoute('rdv');
        }
        $date = new \Datetime($date);
        // return new Response(var_dump($date));

        $rdv = new Rdv();
        $rdv->setUser($this->get('security.token_storage')->getToken()->getUser());
        $rdv->setDateRdv($date);
        $rdv->setHeure($heure);
        $rdv->setSubject($subject);
        $rdv->setMessage($message);
        $rdv->setDateSave(new \Datetime());
        $rdv->setDateUpdate(new \Datetime());

        $em->persist($rdv);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Le rendez-vous à été bien enregistré.');
        return $this->redirectToRoute('my_account');
      }

      return $this->render('AppBundle:Contact:rdv.html.twig', [
      ]);
    }

    public function heuresNonDisponiblesAction(Request $request, $dateRdv)
    {
      $em = $this->getDoctrine()->getManager();
      // return new Response($date);
      // $dateRdv->format('Y-m-d');
      $requete_des_heures = "SELECT r.heure FROM rdv r WHERE r.date_rdv = :dateRdv;";
      $statement = $em->getConnection()->prepare($requete_des_heures);
      $statement->bindValue('dateRdv', $dateRdv);
      $statement->execute();
      $heures = $statement->fetchAll();


      $response = new JsonResponse($heures);
      return $response;

    }
}
