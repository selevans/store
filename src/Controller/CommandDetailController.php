<?php

namespace App\Controller;

use App\Entity\CommandDetail;
use App\Entity\Product;
use App\Entity\Command;
use App\Form\CommandDetailType;
use App\Repository\CommandDetailRepository;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CommandController;

/**
 * @Route("/details")
 */
class CommandDetailController extends AbstractController
{
    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="command_detail_index", methods={"GET"})
     */
    public function index(CommandDetailRepository $commandDetailRepository): Response
    {
        return $this->render('command_detail/index.html.twig', [
            'command_details' => $commandDetailRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="command_detail_add", methods={"GET","POST"})
     */
    public function addOrderDetail(Request $request)
    {
        $commanddetail = new CommandDetail();
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('App:Product')->findOneBy([
            'id' => $request->get("productID")
        ]);
        if ($this->session->get("commandId") == null) {
            // probably here I should add this part in the repository or add a service :)
            $command = new Command();
            $command->setCreateAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($command);
            $entityManager->flush();
            $this->session->set('commandId',$command->getId());
        }
        $command = $em->getRepository('App:Command')->findOneBy([
            'id' => $this->session->get("commandId")
        ]);
        $commanddetail->setCommand($command);
        $commanddetail->setProduct($product);
        $commanddetail->setQuantity($request->get("CommandQuantity"));
        $em->persist($commanddetail);
        $em->flush();
        $stock= $product->getQuantity() - $request->get("CommandQuantity");
        $em->getRepository('App:Product')->updateStock($request->get("productID"),$stock);
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/new", name="command_detail_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commandDetail = new CommandDetail();
        $form = $this->createForm(CommandDetailType::class, $commandDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commandDetail);
            $entityManager->flush();

            return $this->redirectToRoute('command_detail_index');
        }

        return $this->render('command_detail/new.html.twig', [
            'command_detail' => $commandDetail,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="command_detail_show", methods={"GET"})
     */
    public function show(CommandDetail $commandDetail): Response
    {
        return $this->render('command_detail/show.html.twig', [
            'command_detail' => $commandDetail,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="command_detail_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CommandDetail $commandDetail): Response
    {
        $form = $this->createForm(CommandDetailType::class, $commandDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('command_detail_index');
        }

        return $this->render('command_detail/edit.html.twig', [
            'command_detail' => $commandDetail,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="command_detail_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CommandDetail $commandDetail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandDetail->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commandDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('command_detail_index');
    }


}
