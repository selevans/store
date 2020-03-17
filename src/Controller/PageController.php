<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends  AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home (){
        return $this->render('pages/home.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about (){
        return $this->render('pages/about.html.twig');
    }
}

