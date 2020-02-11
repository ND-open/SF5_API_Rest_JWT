<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Unicorn;
use App\Form\UnicornType;

/**
 * Unicorn controller.
 * @Route("/api/unicorn")
 */
class UnicornController extends AbstractFOSRestController
{
    /**
     * Get all Unicorns.
     * @Rest\Get("")
     *
     * @return Response
     */
    public function getAll()
    {
        $unicorns = $this->getDoctrine()->getRepository(Unicorn::class)->findall(); //Find all Unicorns from DB
        return $this->handleView($this->view($unicorns, Response::HTTP_OK));
    }

    /**
     * Get a Unicorn by it's id.
     * @Rest\Get("/{id}")
     *
     * @return Response
     */
    public function getOne(int $id)
    {
        $unicorn = $this->getDoctrine()->getRepository(Unicorn::class)->findOneBy(['id' => $id]); //Find one Unicorn from DB

        if (!$unicorn) { //If not found
            return $this->handleView($this->view(["message" => 'Not Found'], Response::HTTP_NOT_FOUND));
        }

        return $this->handleView($this->view($unicorn, Response::HTTP_OK));
    }

    /**
     * Post one Unicorn.
     * @Rest\Post("")
     *
     * @return Response
     */
    public function postOne(Request $request)
    {
        $data = json_decode($request->getContent(), true);  //Get data from post

        $unicorn = new Unicorn(); //Create a new Unicorn and submit form to bind data
        $form = $this->createForm(UnicornType::class, $unicorn);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) { //If no errors
            $em = $this->getDoctrine()->getManager();
            $em->persist($unicorn);
            $em->flush(); //Persist in db

            return $this->handleView($this->view($unicorn, Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * Put one Unicorn.
     * @Rest\Put("/{id}")
     *
     * @return Response
     */
    public function updateOne(Request $request, int $id)
    {
        $data = json_decode($request->getContent(), true);  //Get data from post

        $unicorn = $this->getDoctrine()->getRepository(Unicorn::class)->findOneBy(['id' => $id]); //Find all Unicorns from DB

        if (!$unicorn) { //If not found
            return $this->handleView($this->view(["message" => 'Not Found'], Response::HTTP_NOT_FOUND));
        }

        $form = $this->createForm(UnicornType::class, $unicorn);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) { //If no errors
            $em = $this->getDoctrine()->getManager();
            $em->persist($unicorn);
            $em->flush(); //Persist in db

            return $this->handleView($this->view($unicorn, Response::HTTP_OK));
        }

        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    /**
     * Delete one Unicorn.
     * @Rest\Delete("/{id}")
     *
     * @return Response
     */
    public function deleteOne(int $id)
    {
        $unicorn = $this->getDoctrine()->getRepository(Unicorn::class)->findOneBy(['id' => $id]); //Find one Unicorn from DB

        if (!$unicorn) { //If not found
            return $this->handleView($this->view(["message" => 'Not Found'], Response::HTTP_NOT_FOUND));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($unicorn);
        $em->flush(); //Remove from db

        return $this->handleView($this->view([], Response::HTTP_OK));
    }
}
