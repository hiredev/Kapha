<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Form\ContactoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="bienvenido")
     */
    public function index()
    {
        $teachers = $this->getDoctrine()->getRepository('App:Teacher')->findBy([
            'isActive' => true,
        ], [
            'id' => "ASC"
        ], 2);

        $courses = $this->getDoctrine()->getRepository('App:Course')->findBy([
            'isActive' => true,
        ], [
            'id' => "ASC"
        ], 4);

        $plans = $this->getDoctrine()->getRepository('App:PaymentPlan')->findBy([
            'isActive' => true,
        ], [
            'id' => "ASC"
        ], 3);

        return $this->render('default/bienvenido.html.twig', [
            'controller_name' => 'DefaultController',
            'teachers' => $teachers,
            'courses' => $courses,
            'plans' => $plans,
        ]);
    }

    public function menu()
    {
        return $this->render('default/menu.html.twig');
    }

    /**
     * @Route("/nosotros", name="nosotros")
     */
    public function nosotros()
    {
        return $this->render('default/nosotros.html.twig');
    }

    /**
     * @Route("/privacy_policy", name="privacy_policy")
     */
    public function privacy_policy()
    {
        return $this->render('default/privacy.html.twig');
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        return $this->render('default/faq.html.twig');
    }

    /**
     * @Route("/ayuda", name="ayuda")
     */
    public function ayuda(Request $request)
    {
        $contato = new Contacto();
        $form = $this->createForm(ContactoType::class, $contato);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contacto = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contato);
            $entityManager->flush();

            $this->addFlash('exito', 'Mensaje enviado!!');
            $this->redirectToRoute('ayuda');
        }

        return $this->render('default/ayuda.html.twig',
            ['formulario' => $form->createView()]
        );
    }
}
