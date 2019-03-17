<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Catalog;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatalogCommand extends BaseCommand
{
    const COMMAND_NAME = 'catalog:part';

    const XLS = 'Xls';

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Catalog for part')
        ;
    }

    protected function import(InputInterface $input, OutputInterface $output)
    {
        $worksheet  = $this->createReader('synonym.xls');
        $highestRow = $worksheet->getHighestRow();

        /** @var ProgressBar $progress */
        $progress = $this->runProgressBar($output, $highestRow);

        for ($row = 2; $row <= $highestRow; $row++) {
            $value = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

            $catalog = new Catalog();
            $catalog->setName($value);

            $this->em->persist($catalog);

            if (0 === ($row % self::BATCH_SIZE)) {
                $this->em->flush();
                $this->em->clear(Catalog::class);

                $progress->setMessage("*Load...*", 'status');
                $progress->advance(self::BATCH_SIZE);
            }
        }

        $this->em->flush();
        $this->em->clear();

        $progress->finish();
    }

    public function createReader(string $file): Worksheet
    {
        $path = $this->params->get('catalog') . DIRECTORY_SEPARATOR . $file;

        $reader = IOFactory::createReader(self::XLS);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
        $worksheet   = $spreadsheet->getActiveSheet();

        return $worksheet;
    }
}