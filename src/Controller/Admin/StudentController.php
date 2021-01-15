<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Payment;
use App\Entity\PaymentPlan;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Lesson;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class StudentController extends AbstractDashboardController
{
    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        return $this->redirect($routeBuilder->setController(LessonCrudController::class)->generateUrl());
    }

    /**
     * @Route("/student/subscription", name="student_subscription")
     */
    public function subscription(): Response
    {

        $plans = $this->getDoctrine()->getRepository(PaymentPlan::class)->findBy([
            'isActive' => true,
        ]);

        $payments = $this->getDoctrine()->getRepository(Payment::class)->findBy([
            'student' => $this->getUser()->getStudent()
        ]);

//        $paypal =

        return $this->render("student/subscription.html.twig", [
            'payments' => $payments,
            'plans' => $plans,
        ]);
    }

    /**
     * @Route("/student/subscription/buy", name="student_subscription_buy")
     */
    public function subscriptionBuy(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $payment = new Payment();
        $payment->setStudent($this->getUser()->getStudent());
        $payment->setMethod('test');
        $payment->setAmount(123);
        $payment->setTransaction(1);
        $payment->setPlan($em->getReference(PaymentPlan::class, $request->get('id')));

        $em->persist($payment);
        $em->flush();

        return $this->redirectToRoute('student_subscription');
    }


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Wesdap');
    }

    public function configureMenuItems(): iterable
    {

        $lastPayment = $this->getDoctrine()->getRepository('App:Payment')->findLastPayment($this->getUser()->getStudent());
        $isSubscribed = false;

        if ($lastPayment->getDate()->diff(new \DateTime())->days <= $lastPayment->getPlan()->getPeriod()) {
            $isSubscribed = true;
        }

        yield MenuItem::section('Programas');
        yield MenuItem::linkToCrud('Programas', 'fa fa-users', Course::class);

        if ($isSubscribed) {
            yield MenuItem::section('Aulas');
            yield MenuItem::linkToCrud('Aulas', 'fa fa-users', Lesson::class);
        }

        yield MenuItem::section('Perfil');
        yield MenuItem::linktoRoute('Subscription', 'fa fa-money', "student_subscription");
        if ($this->getUser()->getStudent() != null) {
            yield MenuItem::linkToCrud('Perfil', 'fa fa-users', Student::class)
                ->setAction('edit')
                ->setEntityId($this->getUser()->getStudent()->getId());
        }

        yield MenuItem::linkToCrud('Acceso', 'fa fa-users', User::class)
            ->setAction('edit')
            ->setEntityId($this->getUser()->getId());
    }
}