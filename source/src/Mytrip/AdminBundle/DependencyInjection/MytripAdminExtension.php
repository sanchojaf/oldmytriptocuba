<?php

namespace Mytrip\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MytripAdminExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);		
		
		$container->setParameter('mytrip_admin.helper.date', $config['helper']['date']);		
		$container->setParameter('mytrip_admin.helper.amazon', $config['helper']['amazon']);
		$container->setParameter('mytrip_admin.helper.beanstream', $config['helper']['beanstream']);
		$container->setParameter('mytrip_admin.helper.facebook', $config['helper']['facebook']);
		$container->setParameter('mytrip_admin.helper.twitter', $config['helper']['twitter']);
		$container->setParameter('mytrip_admin.helper.google', $config['helper']['google']);
		$container->setParameter('mytrip_admin.helper.recaptcha', $config['helper']['recaptcha']);
		$container->setParameter('mytrip_admin.helper.sms', $config['helper']['sms']);
		$container->setParameter('mytrip_admin.helper.globalone', $config['helper']['globalone']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
