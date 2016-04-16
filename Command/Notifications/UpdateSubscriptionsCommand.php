<?php
namespace Kasifi\GoogleDriveBundle\Command\Notifications;

use Kasifi\GoogleDriveBundle\Entity\NotificationChannel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateSubscriptionsCommand extends ContainerAwareCommand
{
    /**
     * @var SymfonyStyle
     */
    private $io;

    protected function configure()
    {
        $this
            ->setName('gdrive:notifications:update-subscription')
            ->setDescription('Update expired notification channel subscriptions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Init
        $this->io = new SymfonyStyle($input, $output);
        $channelManager = $this->getContainer()->get('kasifi_gdrive.channel_manager');
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $channels = $em->getRepository('GoogleDriveBundle:NotificationChannel')->findAll();
        foreach ($channels as $channel) {
            /** @var $channel NotificationChannel */
            $now = new \DateTime();
            $expiration = $channel->getExpiration();

            if ($now < $expiration) {
                $expirationDiff = $now->diff($channel->getExpiration());
                $this->io->comment('Still fresh, expires in ' . $expirationDiff->format("%dd, %Hh, %Im and %Ss."));
            } else {
                $this->io->comment('Expired. Renewing...');
                $channel = $channelManager->update($channel);
                $this->io->success('Updated. Next expiration: ' . $channel->getExpiration()->format('Y/m/d H:i:s'));
            }
        }
    }
}