<?php
/**
 * Company: CodeTiburon
 * Date: 2014-03-27
 *
 * DependencyInjection Pattern
 */
namespace CodeTiburon\DependencyInjection;

interface DependencyInjectionInterface
{
    public function instantiate($class);
}