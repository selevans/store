<?php


namespace App\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractController
{
    /**
     * ProductController constructor.
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("product/index",name="product.index")
     */
    public function index() {
        return $this->render('product/index.html.twig', ['products'  => $this->session->get('products')]);

    }

    /**
     * @Route("product/show{id}",name="product.show" ,methods={"GET","HEAD"})
     * @param $id
     * @return Response
     */
    public function show($id){
        return $this->render('product/add.html.twig');
    }


    /**
     * @Route("product/add",name="product.add")
     */
    public function add(){
        return $this->render('product/add.html.twig');
    }

    /**
     * @Route("product/persist",name="product.persist")
     * @param Request $request
     * @return RedirectResponse
     */
    public function persist(Request $request){
        if($request->getMethod() === "POST"){
            if($this->session->has('products')){
                $products = $this->session->get('products');
                $products[] = new Product(end($products)->getId()+1, $request->get('name'), $request->get('price'), $request->get('quantity'), $request->get('description'), $request->get('image'), date("d/m/Y"));
                $this->session->set('products', $products);
            }else {
                $products[] = new Product(1, $request->get('name'), $request->get('price'), $request->get('quantity'), $request->get('description'), $request->get('image'), date("d/m/Y"));
                $this->session->set('products', $products);
            }
        }
        return $this->redirectToRoute('product.index');
    }

    /**
     * @Route("product/edit",name="product.edit")
     */
    public function edit(){
        return $this->render('product/edit.html.twig');
    }
}