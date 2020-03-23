<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\CommandType;
use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @Route("/command")
 */
class CommandController extends AbstractController
{

    private $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="command_index", methods={"GET"})
     */
    public function index(CommandRepository $commandRepository): Response
    {
        return $this->render('command/index.html.twig', [
            'commands' => $commandRepository->findAll(),
        ]);
    }


    /**
     * @Route("/add", name="command_add", methods={"GET","POST"})
     */
    public function addCommand(): Response
    {
        $command = new Command();
        $command->setCreateAt(new \DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($command);
        $entityManager->flush();
        $this->session->set('commandId',$command->getId());
        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/new", name="command_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $command = new Command();
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('command_index');
        }

        return $this->render('command/new.html.twig', [
            'command' => $command,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="command_show", methods={"GET"})
     */
    public function show(Command $command): Response
    {
        return $this->render('command/show.html.twig', [
            'command' => $command,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="command_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Command $command): Response
    {
        $form = $this->createForm(CommandType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('command_index');
        }

        return $this->render('command/edit.html.twig', [
            'command' => $command,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="command_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Command $command): Response
    {
        if ($this->isCsrfTokenValid('delete'.$command->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($command);
            $entityManager->flush();
        }

        return $this->redirectToRoute('command_index');
    }

}
