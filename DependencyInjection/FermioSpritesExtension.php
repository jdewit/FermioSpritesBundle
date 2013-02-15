<?php

/*
 * This file is part of the FermioSpritesBundle package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\SpritesBundle\DependencyInjection;

use Fermio\Sprites\Command\GenerateSpritesCommand;
use Imagine\Image\Color;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Prepare the services for the SpritesBundle
 *
 * @author David Buchmann <mail@davidbu.ch>
 */
class FermioSpritesExtension extends Extension
{
    /**
     * Loads the services based on your application configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader =  new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('sprites.xml');

        $driver = isset($config['defaults']['driver']) ? $config['defaults']['driver'] : null;
        $container->setParameter($this->getAlias().'.imagine.class', GenerateSpritesCommand::getImagineClass($driver));

        $spritesets = array();
        if (!empty($config['spritesets'])) {
            foreach ($config['spritesets'] as $name => $spriteset) {
                // shortcut to have dynamic instead of explicit processor service name
                if (isset($spriteset['dynamic'])) {
                    $spriteset['processor'] = $spriteset['dynamic'] ?
                        'fermio_sprites.dynamic_processor' :
                        'fermio_sprites.fixed_processor';
                    unset($spriteset['dynamic']);
                }
                // default to dynamic if nothing is set
                if (! isset($spriteset['processor'])) {
                    $spriteset['processor'] = 'fermio_sprites.dynamic_processor';
                }

                $spritesets[$name] = array_merge($config['defaults'], $spriteset);
            }
        }
        $container->setParameter($this->getAlias().'.configuration', $spritesets);
    }
}
