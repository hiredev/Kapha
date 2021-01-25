<?php

namespace App\Controller;

use App\Entity\Contacto;
use App\Entity\Categoria;
use App\Entity\Pagina;
use App\Form\ContactoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends BaseController
{

    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="bienvenido")
     */
    public function index()
    {
        $teachers = $this->getDoctrine()->getRepository('App:Teacher')->findBy([
            'isActive' => true,
        ], [
            'displayOrder' => "ASC"
        ], 2);

        $courses = $this->getDoctrine()->getRepository('App:Course')->findBy([
            'isActive' => true,
        ], [
            'displayOrder' => "ASC"
        ], 4);

        $plans = $this->getDoctrine()->getRepository('App:PaymentPlan')->findBy([
            'isActive' => true,
        ], [
            'displayOrder' => "ASC"
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
        $page = $this->getDoctrine()->getRepository(Pagina::class)->findOneBy([
            'path' => 'nosotros'
        ]);

        return $this->render('default/nosotros.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/ver_maestros/{categoria}", name="teachers")
     */
    public function indexCategory($categoria = '')
    {
        $categorias = $this->em->getRepository(Categoria::class)->findAll();

        return $this->render('teacher/index.html.twig', [
            'categorias' => $categorias,
        ]);
    }

    /**
     * @Route("/privacy_policy", name="privacy_policy")
     */
    public function privacy_policy()
    {
        $page = $this->getDoctrine()->getRepository(Pagina::class)->findOneBy([
            'path' => 'privacy_policy'
        ]);

        return $this->render('default/privacy.html.twig', [
            'page' => $page,
        ]);
    }


    /**
     * @Route("/faq", name="faq")
     */
    public function faq()
    {
        $page = $this->getDoctrine()->getRepository(Pagina::class)->findOneBy([
            'path' => 'faq'
        ]);

        return $this->render('default/page.html.twig', [
            'page' => $page,
        ]);
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
