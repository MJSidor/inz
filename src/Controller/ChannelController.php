<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Form\ChannelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ChannelController
 * @package App\Controller
 * @Route("/channel", name="app_user_channel")
 */
class ChannelController extends AbstractController
{
    /**
     * @Route("/edit", name="_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY", message="Brak dostępu.")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function editChannel(Request $request, EntityManagerInterface $entityManager)
    {
        $channel = $this->getUser()->getChannel();
        if ($channel === null) {
            $channel = new Channel($this->getUser()->getName());
            $channel->setUser($this->getUser());
        }

        $form = $this->createForm(ChannelFormType::class, $channel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Edytowano kanał!');

            return $this->redirectToRoute('app_user_channel', ['id' => $channel->getId()]);
        }

        return $this->render('channel/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="")
     * @param Channel $channel
     * @return Response
     */
    public function index(Channel $channel)
    {
        return $this->render('channel/index.html.twig', [
            'channel' => $channel
        ]);
    }


}
