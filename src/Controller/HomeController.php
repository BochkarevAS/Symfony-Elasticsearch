<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @Route("/", name="homepage", methods="GET|POST")
     */
    public function index(Request $request)
    {
        $xml  = $this->context->handle('xml');
        $json = $this->context->handle('json');

        return $this->render('base.html.twig');
    }
}