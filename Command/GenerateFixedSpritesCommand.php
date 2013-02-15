<?php

/*
 * This file is part of the FermioSpritesBundle package.
 *
 * (c) Pierre Minnieur <pm@pierre-minnieur.de>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\SpritesBundle\Command;

use Fermio\Sprites\Command\GenerateFixedSpritesCommand as BaseCommand;

class GenerateFixedSpritesCommand extends BaseCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('fermio:sprites:generate:fixed')
            ->setDescription('Generate an image sprite and CSS stylesheet with a fixed width dimension using command line arguments.')
        ;
    }
}
