<?php

declare(strict_types=1);

namespace App\EventListener;

use Elastica\Index\Settings;
use FOS\ElasticaBundle\Event\IndexPopulateEvent;
use FOS\ElasticaBundle\Index\IndexManager;
use Doctrine\Common\EventSubscriber;

/**
 * При импортирте больших объемов данных.
 * Можете быть высокая нагрузка на узел данных, поскольку Elasticsearch выполняет асинхронное обновление сегментов.
 * Эот слушатель выставляет специальные настройки для массовой вставки а потом возвращает настройки до обычного состояния.
 * Тоесть вначале происходит индексация а потом уже обновление индекса.
 */
class ElasticaPopulateListener implements EventSubscriber
{
    /**
     * @var IndexManager
     */
    private $indexManager;

    /**
     * @param IndexManager $indexManager
     */
    public function __construct(IndexManager $indexManager)
    {
        $this->indexManager = $indexManager;
    }

    public function getSubscribedEvents()
    {
        return [
            IndexPopulateEvent::PRE_INDEX_POPULATE  => 'preIndexPopulate',
            IndexPopulateEvent::POST_INDEX_POPULATE => 'postIndexPopulate'
        ];
    }

    public function preIndexPopulate(IndexPopulateEvent $event)
    {
        $index    = $this->indexManager->getIndex($event->getIndex());
        $settings = $index->getSettings();
        $settings->setRefreshInterval(-1);
    }

    public function postIndexPopulate(IndexPopulateEvent $event)
    {
        $index = $this->indexManager->getIndex($event->getIndex());
        $index->getClient()->request('_forcemerge', 'POST', ['max_num_segments' => 5]);
        $index->getSettings()->setRefreshInterval(Settings::DEFAULT_REFRESH_INTERVAL);
    }
}