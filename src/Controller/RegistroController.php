<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\PaymentPlan;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use App\Form\StudentType;
use App\Form\TeacherType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistroController extends BaseController
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

            $this->get("session")->set("new_user_id", $student->getId());

            return $this->redirectToRoute('registro_student_step2');
        }

        return $this->render(
            'registro/student.html.twig',
            ['formulario' => $form->createView()]
        );
    }

    /**
     * @Route("/registro/student/step2", name="registro_student_step2")
     */
    public function studentStep2(Request $request)
    {
        $id = $this->get("session")->get("new_user_id");
        if (!$id) {
            return $this->redirectToRoute('registro_student');
        }
        $student = $this->getDoctrine()->getRepository("App:Student")->find($id);
        if (!$student) {
            return $this->redirectToRoute('registro_student');
        }

        $plans = $this->getDoctrine()->getRepository("App:PaymentPlan")->findBy([
            'isActive' => true,
        ]);

        return $this->render("registro/step2.html.twig", [
            'plans' => $plans,
        ]);


        //        $student
    }

    /**
     * @Route("/registro/student/step3/{id}", name="registro_student_step3")
     */
    public function studentStep3(Request $request, PaymentPlan $paymentPlan)
    {
        if (!$paymentPlan)
            return $this->redirectToRoute('registro_student');

        $id = $this->get("session")->get("new_user_id");
        if (!$id)
            return $this->redirectToRoute('registro_student');

        $student = $this->getDoctrine()->getRepository("App:Student")->find($id);

        if (!$student)
            return $this->redirectToRoute('registro_student');


        return $this->render("registro/step3.html.twig", [
            'plan' => $paymentPlan,
            'student' => $student,
        ]);
    }

    /**
     * @Route("/registro/student/step3/{id}/verify", name="registro_student_step3_verify")
     */
    public function studentStep3verisy(Request $request, PaymentPlan $paymentPlan)
    {


        $paypalObj = json_decode($request->getContent());
        if ($paypalObj->status == "COMPLETED") {
            $id = $this->get("session")->get("new_user_id");
            $student = $this->getDoctrine()->getRepository("App:Student")->find($id);

            $em = $this->getDoctrine()->getManager();

            $plan = $this->getDoctrine()->getRepository(PaymentPlan::class)->find($request->get("id"));

            $payment = new Payment();
            $payment->setPlan($em->getReference(PaymentPlan::class, $plan->getId()));
            $payment->setTransaction($paypalObj->id);
            $payment->setPayload($request->getContent());
            $payment->setStudent($student);
            $payment->setAmount($plan->getAmount());
            $payment->setMethod("PayPal");

            $em->persist($payment);
            $em->flush();

            return new JsonResponse([
                'status' => 'SUCCESS',
            ]);
        }
        return new JsonResponse([
            'status' => 'FAILED',
        ]);

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