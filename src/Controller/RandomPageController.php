<?php


namespace App\Controller;


use App\Entity\Pagina;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RandomPageController extends BaseController
{

    public function randomPage(Request $request, $path)
    {
        $page = $this->getDoctrine()->getRepository(Pagina::class)->findOneBy([
            'path' => $path
        ]);
        return $this->render('default/page.html.twig', [
            'page' => $page
        ]);
    }
}