<?php
/**
 * This file is part of the BEAR.Framework package
 *
 * @package BEAR.Framework
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Sunday\Inject;

use Ray\Di\InjectorInterface as Di;
use Ray\Di\Di\Inject;

/**
 * Inject injector
 *
 * @package    BEAR.Framework
 * @subpackage Inject
 */
trait InjectorInject
{
    /**
     * Dependency injector
     *
     * @var Di
     */
    private $injector;

    /**
     * Injector setter
     *
     * @param Di $injector
     *
     * @Inject
     */
    public function setInjector(Di $injector)
    {
        $this->injector = $injector;
    }
}