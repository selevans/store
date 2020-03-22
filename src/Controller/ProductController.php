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
     * @Route("product/show/{id}",name="product.show" ,requirements={"id": "\d+"}, methods={"GET","HEAD"})
     * @param $id
     * @return Response
     */
    public function show($id){
        $products = $this->session->get('products');
        $product = [];
        foreach ($products as $p) {
            if($p->getId() == $id){
                $product = $p;
            }
        }
        return $this->render('product/show.html.twig',['product' => $product]);
    }


    /**
     * @Route("product/add",name="product.add")
     */
    public function add(){
        return $this->render('product/add.html.twig');
    }

    /**
     * @Route("product/persist",name="product.persist",methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function persist(Request $request){
        if($request->getMethod() === "POST"){
            if($this->session->has('products')){
                $products = $this->session->get('products');
                $products[] = new Product(end($products)->getId()+1, $request->get('name'), $request->get('price'), $request->get('quantity'), $request->get('description'), $request->get('image'), date("d/m/Y"));

            }else {
                $products[] = new Product(1, $request->get('name'), $request->get('price'), $request->get('quantity'), $request->get('description'), $request->get('image'), date("d/m/Y"));

            }
            $this->session->set('products', $products);
            $this->addFlash('add', 'The product is added successfully !');
        }
        return $this->redirectToRoute('product.index');
    }

    /**
     * @Route("product/edit",name="product.edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request){
        if ($request->getMethod() === "POST"){
            $products = $this->session->get('products');
            $product = [];
            foreach ($products as $p) {
                if ($p->getId() == $request->get('id')){
                    $product = $p;
                }
            }
            return $this->render('product/edit.html.twig',['product' => $product]);
        }
    }

    /**
     * @Route("product/update",name="product.update")
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request){
        if($request->getMethod() === "POST"){
            $products = $this->session->get('products');
            foreach ($products as $p) {
                if ($p->getId() == $request->get('id')){
                        $p->setName($request->get('name'));
                        $p->setPrice($request->get('price'));
                        $p->setQuantity($request->get('quantity'));
                        $p->setDescription($request->get('description'));
                        $p->setImageUrl($request->get('image'));
                        $this->session->set('products', $products);
                        $this->addFlash('success', 'The product is updated successfully !');
                }
            }
        }
        return $this->redirectToRoute('product.index');
    }

    /**
     * @Route("product/delete/{id}",name="product.delete")
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id)
    {
        $products = $this->session->get('products');
        foreach ($products as $key => $product) {
            if ($product->getId() == $id){
                unset($products[$key]);
                $this->addFlash('danger', 'the product is deleted !');
            }
        }
        $this->session->set('products', $products);
        return $this->redirectToRoute('product.index');
    }
}