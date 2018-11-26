<?php
// src/Controller/LuckyController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServicesController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('AppBundle:Services:index.html.twig', [
        ]);
    }


    public function constitutiondossiercampusfranceAction()
    {
        return $this->render('AppBundle:Services:constitutiondossiercampusfrance.html.twig', [
        ]);
    }


    public function candidatureuniversitesAction()
    {
        return $this->render('AppBundle:Services:candidatureuniversites.html.twig', [
        ]);
    }


    public function logementAction()
    {
        return $this->render('AppBundle:Services:logement.html.twig', [
        ]);
    }


    public function constitutiondossiervisaAction()
    {
        return $this->render('AppBundle:Services:constitutiondossiervisa.html.twig', [
        ]);
    }

}
