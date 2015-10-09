<?php
namespace Payum\Bundle\PayumBundle\DependencyInjection\Factory\Payment;

use Payum\Core\Bridge\Twig\TwigFactory;
use Payum\Core\Exception\RuntimeException;
use Payum\Klarna\Checkout\Constants;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;

class KlarnaCheckoutPaymentFactory extends AbstractPaymentFactory implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(ContainerBuilder $container, $contextName, array $config)
    {
        if (false == class_exists('Payum\Klarna\Checkout\PaymentFactory')) {
            throw new RuntimeException('Cannot find klarna checkout payment factory class. Have you installed payum/klarna-checkout package?');
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../../../Resources/config/payment'));
        $loader->load('klarna_checkout.xml');

        return parent::create($container, $contextName, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'klarna_checkout';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        parent::addConfiguration($builder);
        
        $builder->children()
            ->scalarNode('secret')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('merchant_id')->isRequired()->cannotBeEmpty()->end()
            ->booleanNode('sandbox')->defaultTrue()->end()
        ->end();
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('twig', array(
            'paths' => array_flip(array_filter(array(
                'PayumCore' => TwigFactory::guessViewsPath('Payum\Core\Payment'),
                'PayumKlarnaCheckout' => TwigFactory::guessViewsPath('Payum\Klarna\Checkout\PaymentFactory'),
            )))
        ));
    }

    /**
     * {@inheritDoc}
     */
    protected function addApis(Definition $paymentDefinition, ContainerBuilder $container, $contextName, array $config)
    {
        $internalConnectorDefinition = new Definition('Klarna_Checkout_ConnectorInterface');
        $internalConnectorDefinition->setFactoryClass('Klarna_Checkout_Connector');
        $internalConnectorDefinition->setFactoryMethod('create');
        $internalConnectorDefinition->addArgument($config['secret']);
        $internalConnectorId = 'payum.context.'.$contextName.'.internal_connector';
        $container->setDefinition($internalConnectorId, $internalConnectorDefinition);

        $connectorDefinition = new Definition('%payum.klarna.checkout.connector.class%');
        $connectorDefinition->addArgument(new Reference($internalConnectorId));
        $connectorDefinition->addArgument($config['merchant_id']);
        $connectorDefinition->addArgument($config['sandbox'] ?
            Constants::BASE_URI_SANDBOX :
            Constants::BASE_URI_LIVE
        );
        $connectorDefinition->addArgument(Constants::CONTENT_TYPE_V2_PLUS_JSON);
        $connectorId = 'payum.context.'.$contextName.'.connector';
        $container->setDefinition($connectorId, $connectorDefinition);

        $paymentDefinition->addMethodCall('addApi', array(new Reference($connectorId)));
    }
}