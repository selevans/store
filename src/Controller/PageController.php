<?php

namespace App\Controller;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class PageController extends  AbstractController
{
    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/", name="homepage")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function home(ProductRepository $productRepository): Response
    {
        return $this->render('pages/home.html.twig', ['products' => $productRepository->getLastThreeProducts() ]);
    }


    /**
     * @Route("/about", name="about")
     */
    public function about (){
        return $this->render('pages/about.html.twig');
    }
}

