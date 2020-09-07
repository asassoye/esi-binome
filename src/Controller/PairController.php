<?php
/*
 * Copyright (C) 2020 Andrew SASSOYE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Controller;

use App\Entity\Pair\Pair;
use App\Form\Type\PairFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PairController
 *
 * @author      Andrew SASSOYE <andrew@sassoye.be>
 * @copyright   Copyright (C) 2020 Andrew SASSOYE
 * @license     https://www.gnu.org/licenses/agpl-3.0 AGPL-3.0
 *
 * @Route("/pair")
 * @IsGranted("ROLE_STUDENT")
 */
class PairController extends AbstractController
{
    /**
     * @Route("/", name="app_pair_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->redirectToRoute('app_pair_status');
    }

    /**
     *
     * @Route("/status", name="app_pair_status", methods={"GET"})
     */
    public function status(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $pairs = $em->getRepository(Pair::class)->findByStudentId($this->getUser()->getId());

        if (null === $pairs || empty($pairs)) {
            return $this->redirectToRoute('app_pair_create');
        }

        $pair = $pairs[0]->getStudent1() == $this->getUser() ? $pairs[0]->getStudent2() : $pairs[0]->getStudent1();

        if ($pairs[0]->isAccepted() == true) {
            return $this->render('pair/accepted.html.twig', array(
                'pair' => $pair
            ));
        }

        if ($pairs[0]->getStudent2() == $this->getUser()) {
            return $this->render('pair/requestList.html.twig', array(
                "pairs" => $pairs
            ));
        }

        return $this->render('pair/waiting.html.twig', array(
            "pair" => $pair,
            "pair_id" => $pairs[0]->getId()
        ));

    }

    /**
     *
     * @Route("/create", name="app_pair_create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pairs = $em->getRepository(Pair::class)->findByStudentId($this->getUser()->getId());

        if (!(null === $pairs || empty($pairs))) {
            return $this->redirectToRoute('app_pair_status');
        }

        $form = $this->createForm(PairFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData()["student"];

            $pair = new Pair();
            $pair->setStudent1($this->getUser());
            $pair->setStudent2($user);

            $em->persist($pair);
            $em->flush();

            return $this->redirectToRoute('app_pair_status');
        }

        return $this->render("pair/requestForm.html.twig", array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param int $id
     *
     * @Route("/accept/{id}", name="app_pair_accept", methods={"GET"})
     */
    public function accept(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $pair = $em
            ->getRepository(Pair::class)
            ->findOneBy(array(
                "id" => $id
            ));

        if (null === $pair) {
            throw new NotFoundHttpException("Cet id est invalide.");
        }

        if ($pair->getStudent2() != $this->getUser()) {
            throw new AccessDeniedHttpException("Vous n'avez pas le droit d'accepter la demande d'un autre étudiant");
        }

        $pair->setAccepted(true);
        $em->persist($pair);
        $em->flush();

        $em->getRepository(Pair::class)->refuseAllExceptOne($this->getUser()->getId(), $pair->getId());

        return $this->redirectToRoute('app_pair_status');
    }

    /**
     * @param int $id
     *
     * @Route("/refuse/{id}", name="app_pair_refuse", methods={"GET"})
     */
    public function refuse(int $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $pair = $em
            ->getRepository(Pair::class)
            ->findOneBy(array(
                "id" => $id
            ));

        if (null === $pair) {
            throw new NotFoundHttpException("Cet id est invalide.");
        }

        if (!($pair->getStudent2() == $this->getUser() || $pair->getStudent1() == $this->getUser())) {
            throw new AccessDeniedHttpException("Vous n'avez pas le droit refuser la demande d'un autre étudiant");
        }

        $em->remove($pair);
        $em->flush();

        return $this->redirectToRoute('app_pair_status');
    }
}
