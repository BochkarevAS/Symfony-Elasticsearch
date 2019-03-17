<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class BaseCommand extends Command
{
    const BATCH_SIZE = 1000;

    protected $em;

    protected $params;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
    {
        $this->em     = $em;
        $this->params = $params;

        parent::__construct();
    }

    abstract protected function import(InputInterface $input, OutputInterface $output);

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->import($input, $output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * Запускаем ProgressBar
     */
    protected function runProgressBar(OutputInterface $output, int $rows)
    {
        $progress = new ProgressBar($output, $rows);
        $progress->setFormat(sprintf('%s item: <info>%%row%%</info>', $progress->getFormatDefinition('debug')));
        $progress->setBarCharacter('<fg=green>⚬</>');
        $progress->setEmptyBarCharacter("<fg=red>⚬</>");
        $progress->setProgressCharacter("<fg=green>➤</>");
        $progress->setFormat("%current%/%max% [%bar%] %percent:3s%%\n🏁  %estimated:-25s%  %memory:20s%\n");
        $progress->start();

        return $progress;
    }
}