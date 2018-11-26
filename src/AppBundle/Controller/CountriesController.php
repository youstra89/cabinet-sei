<?php
// src/Controller/LuckyController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CountriesController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('AppBundle:Countries:index.html.twig', [
        ]);
    }


    public function franceAction()
    {
        return $this->render('AppBundle:Countries:france.html.twig', [
        ]);
    }

    public function belgiqueAction()
    {
        return $this->render('AppBundle:Countries:belgique.html.twig', [
        ]);
    }

    public function canadaAction()
    {
        return $this->render('AppBundle:Countries:canada.html.twig', [
        ]);
    }

    public function marocAction()
    {
        return $this->render('AppBundle:Countries:maroc.html.twig', [
        ]);
    }



    // public function rdv()
    // {
    //
    // }
}
