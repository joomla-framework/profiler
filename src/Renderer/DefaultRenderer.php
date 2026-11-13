<?php

/**
 * Part of the Joomla Framework Profiler Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler\Renderer;

use Joomla\Profiler\ProfilerInterface;
use Joomla\Profiler\ProfilerRendererInterface;

/**
 * Default profiler renderer.
 *
 * @since  1.0
 */
class DefaultRenderer implements ProfilerRendererInterface
{
    /**
     * Render the profiler.
     *
     * The profiler and point names are HTML-escaped.
     *
     * @param   ProfilerInterface  $profiler  The profiler to render.
     *
     * @return  string  The rendered profiler.
     *
     * @since   1.0
     */
    public function render(ProfilerInterface $profiler)
    {
        $render = '';

        $points = $profiler->getPoints();

        $previousTime = 0.0;
        $previousMem  = 0;

        foreach ($points as $point) {
            $render .= sprintf(
                '<code>%s %.3f seconds (+%.3f); %0.2f MB (%s%0.3f) - %s</code>',
                htmlspecialchars($profiler->getName(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                $point->getTime(),
                $point->getTime() - $previousTime,
                $point->getMemoryMegaBytes(),
                ($point->getMemoryMegaBytes() > $previousMem) ? '+' : '',
                $point->getMemoryMegaBytes() - $previousMem,
                htmlspecialchars($point->getName(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            );

            $render .= '<br />';

            /** @var \Joomla\Profiler\ProfilePointInterface $lastPoint */
            $lastPoint = $point;

            $previousTime = $lastPoint->getTime();
            $previousMem  = $lastPoint->getMemoryMegaBytes();
        }

        return $render;
    }
}
