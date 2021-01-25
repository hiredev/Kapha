<?php

namespace App\Controller\Admin;

use App\Entity\Meeting;
use App\Entity\Option;
use App\Entity\Payment;
use App\Entity\PaymentPlan;
use App\Entity\User;
use App\Entity\Pagina;
use App\Entity\Categoria;
use App\Entity\Teacher;
use App\Entity\Student;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\CuentaZoom;
use App\Entity\Contacto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DesignController extends AbstractDashboardController
{
    /**
     * @Route("/admin/design", name="admin_design")
     */
    public function index(): Response
    {

        $header = $this->getDoctrine()->getRepository("App:Option")->find(1);
        $footer = $this->getDoctrine()->getRepository("App:Option")->find(2);

        return $this->render("default/design.html.twig", [
            'header' => json_decode($header->getContent(), true),
            'footer' => json_decode($footer->getContent(), true),


        ]);
    }

    /**
     * @Route("/admin/design/save", name="admin_design_save")
     */
    public function save(Request $request): Response
    {
        switch ($request->get("menu")) {
            case "header":
                $option = $this->getDoctrine()->getRepository(Option::class)->find(1);
                break;
            case "footer":
                $option = $this->getDoctrine()->getRepository(Option::class)->find(2);
                break;

        }

        $content = $request->get('data');
//        $content = str_replace("[[", "[", $content);
//        $content = str_replace("]]", "]", $content);
        $option->setContent($content);

        dump($option);

        $em = $this->getDoctrine()->getManager();
        $em->persist($option);
        $em->flush();

        dump(123);
        die();
    }


}
