<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\Payment;
use App\Entity\PaymentPlan;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Lesson;
use App\Entity\User;

use Symfony\Component\HttpFoundation\JsonResponse;
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

    private function getSubscription()
    {

        /** @var Payment[] $payments */
        $payments = $this->getDoctrine()->getRepository(Payment::class)->findBy([
            'student' => $this->getUser()->getStudent()
        ], ["date" => "DESC"]);

        $isSubscribed = false;
        $lastPayment = false;

        if (count($payments) > 0) {
            $lastPayment = $payments[0];

            if ($lastPayment->getDate()->diff(new \DateTime())->days <= $lastPayment->getPlan()->getPeriod()) {
                $isSubscribed = true;
            }
        }

        return [
            'isSubscribed' => $isSubscribed,
            'lastPayment' => $lastPayment
        ];
    }

    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();
        $subscription = $this->getSubscription();

        if ($subscription['isSubscribed']) {
            return $this->redirect($routeBuilder->setController(LessonCrudController::class)->generateUrl());
        } else {
            return $this->redirectToRoute('student_subscription');
        }
    }

    /**
     * @Route("/student/subscription", name="student_subscription")
     */
    public function subscription(): Response
    {
        $plans = $this->getDoctrine()->getRepository(PaymentPlan::class)->findBy([
            'isActive' => true,
        ]);

        $subscription = $this->getSubscription();

        return $this->render("student/subscription.html.twig", [
//            'payments' => $payments,
            'plans' => $plans,
            'isSubscribed' => $subscription['isSubscribed'],
            'lastPayment' => $subscription['lastPayment'],
        ]);
    }

    /**
     * @Route("/student/subscription/{id}/buy", name="student_subscription_buy")
     */
    public function subscriptionBuy(PaymentPlan $paymentPlan): Response
    {
        return $this->render('student/subscriptionBuy.html.twig', [
            'plan' => $paymentPlan,
        ]);

    }

    /**
     * @Route("/student/verify", name="verify_payment")
     */
    public function verifyPayment(Request $request): Response
    {


        $paypalObj = json_decode($request->getContent());
        if ($paypalObj->status == "COMPLETED") {
            $em = $this->getDoctrine()->getManager();

            $plan = $this->getDoctrine()->getRepository(PaymentPlan::class)->find($request->get("id"));

            $payment = new Payment();
            $payment->setPlan($em->getReference(PaymentPlan::class, $plan->getId()));
            $payment->setTransaction($paypalObj->id);
            $payment->setPayload($request->getContent());
            $payment->setStudent($this->getUser()->getStudent());
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


        dd($paypalObj);
    }


    /**
     * @Route("/student/subscription/buy2", name="student_subscription_buy2")
     */
    public function subscriptionBuy2(Request $request): Response
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

        if ($lastPayment != null && $lastPayment->getDate()->diff(new \DateTime())->days <= $lastPayment->getPlan()->getPeriod()) {
            $isSubscribed = true;
        }

        if ($isSubscribed) {
            yield MenuItem::section('Programas');
            yield MenuItem::linkToCrud('Programas', 'fa fa-users', Course::class);

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