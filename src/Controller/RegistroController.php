<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Entity\Maestro;
use App\Entity\Usuario;
use App\Form\AlumnoType;
use App\Form\MaestroType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index()
    {
        return $this->render('registro/index.html.twig', [
            'registro_alumno' => 'alumno',
            'registro_maestro' => 'maestro'
        ]);
    }

    /**
     * @Route("/registro/alumno", name="registro_alumno")
     */
    public function alumno(Request $request){
        $alumno = new Alumno();
        $form = $this->createForm(AlumnoType::class, $alumno);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $alumno = $form->getData();
            $email = $form->get('email')->getData();            
            $password = $form->get('password')->getData();

            $usuario = new Usuario();
            $usuario->setEmail($email);
            $usuario->setRoles(array('ROLE_USER'));            
 
            $usuario->setPassword($password);    

            $alumno->setUsuario($usuario);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alumno);
            $entityManager->flush();
    
            return $this->redirectToRoute('registro_success');
        }

        return $this->render(
            'registro/alumno.html.twig',
            ['formulario' => $form->createView()]
        );
    }

    /**
     * @Route("/registro/maestro", name="registro_maestro")
     */
    public function maestro(Request $request){
        $maestro = new Maestro();
        $form = $this->createForm(MaestroType::class, $maestro);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $maestro = $form->getData();
            $email = $form->get('email')->getData();            
            $password = $form->get('password')->getData();

            $usuario = new Usuario();
            $usuario->setEmail($email);
            $usuario->setRoles(array('ROLE_USER','ROLE_MAESTRO'));            
            $usuario->setPassword($password);

            $maestro->setUsuario($usuario);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($maestro);
            $entityManager->flush();
    
            return $this->redirectToRoute('registro_success');
        }

        return $this->render(
            'registro/maestro.html.twig',
            ['formulario' => $form->createView()]
        );
    }

    /**
     * @Route("/registro/success", name="registro_success")
     */
    public function success(){
        dd('suceso');
    }
}