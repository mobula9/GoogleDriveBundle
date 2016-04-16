<?php

namespace Kasifi\GoogleDriveBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ProcessorCompilerPass.
 */
class ProcessorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('kasifi_gdrive.notification_handler')) {
            return;
        }

        $definition = $container->findDefinition('kasifi_gdrive.notification_handler');

        $taggedServices = $container->findTaggedServiceIds('kasifi_gdrive.processor');
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addProcessor', [new Reference($id)]);
        }
    }
}
