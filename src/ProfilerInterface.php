<?php

/**
 * Part of the Joomla Framework Profiler Package
 *
 * @copyright  Copyright (C) 2005 - 2021 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Profiler;

/**
 * Interface for Profilers containing a sequence of ProfilePointInterface.
 *
 * @since  1.0
 */
interface ProfilerInterface
{
    /**
     * Get the name of this profiler.
     *
     * @return  string  The name of this profiler.
     *
     * @since   1.0
     */
    public function getName(): string;

    /**
     * Mark a profile point with the given name.
     *
     * @param   string  $name  The profile point name.
     *
     * @return  static  This method is chainable.
     *
     * @since   1.0
     * @throws  \InvalidArgumentException  If a point with that name already exists.
     */
    public function mark(string $name): static;

    /**
     * Check if the profiler has marked the given point.
     *
     * @param   string  $name  The name of the point.
     *
     * @return  boolean  True if the profiler has marked the point, false otherwise.
     *
     * @since   1.0
     */
    public function hasPoint(string $name): bool;

    /**
     * Get the point identified by the given name.
     *
     * @param   string  $name     The name of the point.
     * @param   mixed   $default  The default value if the point hasn't been marked.
     *
     * @return  ProfilePointInterface|mixed  The profile point or the default value.
     *
     * @since   1.0
     */
    public function getPoint(string $name, mixed $default = null): mixed;

    /**
     * Get the points in this profiler (in the order they were marked).
     *
     * @return  ProfilePointInterface[]  An array of points in this profiler.
     *
     * @since   1.0
     */
    public function getPoints(): array;

    /**
     * Set the renderer to render this profiler.
     *
     * @param   ProfilerRendererInterface  $renderer  The renderer.
     *
     * @return  static  This method is chainable.
     *
     * @since   1.0
     */
    public function setRenderer(ProfilerRendererInterface $renderer): static;

    /**
     * Get the currently used renderer in this profiler.
     *
     * @return  ProfilerRendererInterface  The renderer.
     *
     * @since   1.0
     */
    public function getRenderer(): ProfilerRendererInterface;

    /**
     * Render the profiler.
     *
     * @return  string  The rendered profiler.
     *
     * @since   1.0
     */
    public function render(): string;

    /**
     * Get the elapsed time in seconds between the two points.
     *
     * @param   string  $first   The name of the first point.
     * @param   string  $second  The name of the second point.
     *
     * @return  float  The elapsed time between these points in seconds (absolute value).
     *
     * @since   __DEPLOY_VERSION__
     * @throws  \LogicException  If the points were not marked.
     */
    public function getTimeBetween(string $first, string $second): float;

    /**
     * Get the amount of allocated memory in bytes between the two points.
     *
     * @param   string  $first   The name of the first point.
     * @param   string  $second  The name of the second point.
     *
     * @return  integer  The amount of allocated memory between these points in bytes (absolute value).
     *
     * @since   __DEPLOY_VERSION__
     * @throws  \LogicException  If the points were not marked.
     */
    public function getMemoryBytesBetween(string $first, string $second): int;
}
