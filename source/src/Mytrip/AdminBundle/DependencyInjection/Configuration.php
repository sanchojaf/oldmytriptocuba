<?php

namespace Mytrip\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mytrip_admin');
		
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
		 $rootNode->children()  
            ->arrayNode('helper')  
                ->isRequired()  
                ->children()  
                    ->arrayNode('date')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('default_format')->defaultValue('Y-m-d')->cannotBeEmpty()->end()  
                            ->scalarNode('detailed_format')->defaultValue('Y-m-d H:i:s')->cannotBeEmpty()->end() 
                        ->end()  
                    ->end()  
                ->end()
                ->children()  
                    ->arrayNode('amazon')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('awsAccessKey')->end()  
                            ->scalarNode('awsSecretKey')->end() 
							->scalarNode('bucket')->end()  
                            ->scalarNode('url')->end() 
                        ->end()  
                    ->end()  
                ->end()               
			     ->children()  
                    ->arrayNode('beanstream')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('beanstream_merchant_id_usd')->end()  
                            ->scalarNode('beanstream_username_usd')->end()
							->scalarNode('beanstream_password_usd')->end() 
							->scalarNode('beanstream_merchant_id_cad')->end()  
                            ->scalarNode('beanstream_username_cad')->end()
							->scalarNode('beanstream_password_cad')->end()   
                        ->end()  
                    ->end()  
                ->end()
				->children()  
                    ->arrayNode('facebook')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('apikey')->end()  
                            ->scalarNode('apisecretkey')->end() 
                        ->end()  
                    ->end()  
                ->end() 
				->children()  
                    ->arrayNode('twitter')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('apikey')->end()  
                            ->scalarNode('apisecretkey')->end() 
                        ->end()  
                    ->end()  
                ->end()
				->children()  
                    ->arrayNode('recaptcha')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('publickey')->end()  
                            ->scalarNode('privatekey')->end() 
                        ->end()  
                    ->end()  
                ->end()
				->children()  
                    ->arrayNode('sms')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('smsusername')->end()  
                            ->scalarNode('smspassword')->end() 
                        ->end()  
                    ->end()  
                ->end()
				->children()  
                    ->arrayNode('globalone')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('terminalid')->end()  
                            ->scalarNode('secret')->end()  
                            ->scalarNode('multicurrency')->end()   
                            ->scalarNode('testaccount')->end()  
                        ->end()  
                    ->end()  
                ->end()  
				->children()  
                    ->arrayNode('google')  
                        ->isRequired()  
                        ->children()  
                            ->scalarNode('apikey')->end()  
                            ->scalarNode('apisecretkey')->end() 
							->scalarNode('developerkey')->end()
							->scalarNode('product')->end()
                        ->end()  
                    ->end()  
                ->end()
			->end()
        ->end(); 

        return $treeBuilder;
    }
}
