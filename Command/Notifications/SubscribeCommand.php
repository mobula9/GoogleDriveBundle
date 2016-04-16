<?php
namespace Kasifi\GoogleDriveBundle\Command\Notifications;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SubscribeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gdrive:notifications:subscribe')
            ->setDescription('Subscribe to Google Drive notifications.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Init
        $container = $this->getContainer();
        $channelManager = $container->get('kasifi_gdrive.channel_manager');
        $io = new SymfonyStyle($input, $output);

        $config = $container->getParameter('kasifi_gdrive.notification_config');

        foreach ($config as $configItem) {
            $channel = $channelManager->add($configItem['name'], $configItem['type'], $configItem['id']);
            $io->success(
                'Channel "' . $channel->getName() . '" (' . $channel->getId() .
                ') successfully added to the resource: ' . $channel->getResourceId() .
                '. Expiration date: ' . $channel->getExpiration()->format('Y/m/d H:i:s'));
        }
    }
}