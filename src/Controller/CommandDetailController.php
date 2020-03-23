<?php

namespace App\Controller;

use App\Entity\CommandDetail;
use App\Form\CommandDetailType;
use App\Repository\CommandDetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/command/detail")
 */
class CommandDetailController extends AbstractController
{
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
