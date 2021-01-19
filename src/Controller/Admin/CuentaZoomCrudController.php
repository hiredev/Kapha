<?php

namespace App\Controller\Admin;

use App\Entity\CuentaZoom;
use App\Entity\Meeting;
use App\Managers\ZoomManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CuentaZoomCrudController extends AbstractCrudController
{

    private $zoomManager;

    public function __construct(ZoomManager $zoomManager)
    {
        $this->zoomManager = $zoomManager;
    }

    /**
     * @Route("/admin/zoom_token", name="zoom_token")
     */
    public function token(Request $request)
    {

        $auth = $this->zoomManager->zoomOAuth($request->get('code'), $this->generateUrl('zoom_token', array(), UrlGeneratorInterface::ABSOLUTE_URL));

        $zoom = new CuentaZoom();
        $zoom->setClientId($this->getParameter('app.zoom.client_id'));
        $zoom->setCode($request->get("code"));
        $zoom->setAccessToken($auth["access_token"]);
        $zoom->setRefreshToken($auth["refresh_token"]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($zoom);
        $em->flush();

        return $this->redirectToRoute("admin");

    }

    private function generatePassword()
    {
        return rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10) . rand(1, 10);
    }

    /**
     * @Route("/admin/zoom_create", name="zoom_create")
     */
    public function install()
    {
        return $this->redirect('https://zoom.us/oauth/authorize?response_type=code&client_id=' . $this->getParameter('app.zoom.client_id') . '&redirect_uri=' . $this->generateUrl('zoom_token', array(), UrlGeneratorInterface::ABSOLUTE_URL));
    }

    /**
     * @Route("/admin/zoom_create_meeting", name="zoom_create_meeting")
     */
    public function createMeeting()
    {
        $repository = $this->getDoctrine()->getRepository(CuentaZoom::class);

        $meeting = $repository->createMeeting();
        dd($meeting);
    }


    public static function getEntityFqcn(): string
    {
        return CuentaZoom::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $this->crudUrlGenerator = $this->get(CrudUrlGenerator::class);

        return [
            TextField::new('code')
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT);
    }
}
