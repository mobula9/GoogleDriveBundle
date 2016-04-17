<?php
namespace Kasifi\GoogleDriveBundle\Command\Utils;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class SearchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gdrive:utils:search')
            ->setDescription('Search a file (or folder) ID in Google Drive account.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Init
        $driveFinder = $this->getContainer()->get('kasifi_gdrive.finder');
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('Which type?', ['Directory', 'File', 'All'], 0);

        $type = $helper->ask($input, $output, $question);

        // Search a resource in the whole drive
        $search = $io->ask('Enter a search.');
        $search = 'title contains "' . $search . '"';
        switch ($type) {
            case 'Directory':
                $search .= ' and mimeType = "application/vnd.google-apps.folder"';
                break;
            case 'File':
                $search .= ' and mimeType != "application/vnd.google-apps.folder"';
                break;
        }

        $items = $driveFinder->searchResource($search);
        $choices = [];
        foreach ($items as $key => $resource) {
            $choices[] = [$key, $resource->title, $resource->id];
        }
        $io->table(['key', 'title', 'id'], $choices);
        $key = $io->ask('Enter the key to select', 0);
        $item = $items[$key];
        dump($item);
    }
}
