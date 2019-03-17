<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CatalogDto;
use App\Entity\Catalog;
use App\Service\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\ElasticaBundle\Doctrine\RepositoryManager;

class HomeController extends AbstractController
{
    private $context;

    /**
     * @var RepositoryManager
     */
    private $manager;

    public function __construct(Context $context, RepositoryManager $manager)
    {
        $this->context = $context;
        $this->manager = $manager;
    }

    /**
     * @Route("/search", name="search", methods="GET|POST", options={"expose"=true})
     */
    public function search(Request $request)
    {
        $data = new CatalogDto();

        $result = $this->manager->getRepository(Catalog::class)->search($data);
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