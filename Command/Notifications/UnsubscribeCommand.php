<?php
namespace Kasifi\GoogleDriveBundle\Command\Notifications;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UnsubscribeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gdrive:notifications:unsubscribe')
            ->setDescription('Unsubscribe to all Google Drive notification channels');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Init
        $channelManager = $this->getContainer()->get('kasifi_gdrive.channel_manager');
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $io = new SymfonyStyle($input, $output);

        $channels = $em->getRepository('GoogleDriveBundle:NotificationChannel')->findAll();
        foreach ($channels as $channel) {
            $channelManager->remove($channel);
            $io->success('"Channel ' . $channel->getId() . ' / Resource ' . $channel->getResourceId() . '" has been removed.');
        }
    }
}
