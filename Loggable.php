<?php

namespace Kasifi\GoogleDriveBundle;

use Psr\Log\LoggerInterface;

trait Loggable
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     *
     * @param       $message
     * @param array $context
     *
     * @see LoggerInterface::info()
     */
    private function log($message, array $context = [])
    {
        $context = array_merge(['msg' => $message], $context);
        $this->logger && $this->logger->info($message, $context);
    }

    /**
     *
     * @param       $message
     * @param array $context
     *
     * @see LoggerInterface::error()
     */
    private function error($message, $context)
    {
        $context = array_merge(['msg' => $message], $context);
        $this->logger && $this->logger->error($message, $context);
    }
}