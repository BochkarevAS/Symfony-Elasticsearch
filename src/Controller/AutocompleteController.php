<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/suggest")
 */
class AutocompleteController extends AbstractController
{
    /**
     * @var IndexManager
     */
    private $index;

    private $suggestions = [];

    public function __construct(IndexManager $index)
    {
        $this->index = $index;
    }

    /**
     * @Route("/catalog", name="catalog_suggest", options={"expose"=true})
     */
    public function autocompleteCatalog(Request $request)
    {
        $query = $request->get('query', null);

        $search     = $this->index->getIndex('catalog')->createSearch();
        $completion = new \Elastica\Suggest\Completion('search', 'name_suggest');
        $completion->setPrefix($query);
        $completion->setParam('size', 1000);

        $result = $search->search($completion);

        if ($result->countSuggests()) {
            foreach ($result->getSuggests()['search'][0]['options'] as $suggestion) {
                $this->suggestions[] = $suggestion['_source']['output'];
            }
        }

        return new JsonResponse($this->suggestions, 200);
    }
}