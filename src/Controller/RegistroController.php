<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use App\Form\StudentType;
use App\Form\TeacherType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends AbstractController
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;

    }

    /**
     * @Route("/registro", name="registro")
     */
    public function index()
    {
        return $this->render('registro/index.html.twig', [
            'registro_student' => 'Alumno',
            'registro_teacher' => 'Maestro'
        ]);
    }

    /**
     * @Route("/registro/student", name="registro_student")
     */
    public function student(Request $request)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            $user = new User();
            $user->setEmail($email);
            $user->setRoles(array('ROLE_USER'));

            $password = $this->encoder->encodePassword($user, $password);
            $user->setPassword($password);

            $student->setUser($user);

            $this->get("session")->set("new_user", $email);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            return $this->redirectToRoute('registro_success');
        }

        return $this->render(
            'registro/student.html.twig',
            ['formulario' => $form->createView()]
        );
    }

    /**
     * @Route("/registro/teacher", name="registro_teacher")
     */
    public function teacher(Request $request)
    {
        $teacher = new Teacher();
        $form = $this->createForm(TeacherType::class, $teacher);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $form->getData();
            $email = $form->get('email')->getData();
            $this->get("session")->set("new_user", $email);

            $password = $form->get('password')->getData();

            $user = new User();
            $user->setEmail($email);
            $user->setRoles(array('ROLE_USER', 'ROLE_TEACHER'));

            $password = $this->encoder->encodePassword($user, $password);
            $user->setPassword($password);

            $teacher->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();

            return $this->redirectToRoute('registro_success');
        }

        return $this->render(
            'registro/teacher.html.twig',
            ['formulario' => $form->createView()]
        );
    }

    /**
     * @Route("/registro/success", name="registro_success")
     */
    public function success()
    {


        return $this->redirectToRoute("app_login");
    }
}