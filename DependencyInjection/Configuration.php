<?php

namespace Lasso\VmailBundle\DependencyInjection;

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
        $rootNode    = $treeBuilder->root('lasso_vmail')
            ->children()
            ->scalarNode('default_quota')->end()
            ->scalarNode('root_mail_dir')->end()
            ->scalarNode('mailbox_format')->defaultValue('Maildir')->end()
            ->scalarNode('entity_manager_name')->defaultValue('vmail')->end()
            ->end();

        return $treeBuilder;
    }
}
