<?php
/**
 * This file is part of the BEAR.Framework package
 *
 * @package BEAR.Framework
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Sunday\Framework;

use Aura\Autoload\Loader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Framework
 *
 * @package    BEAR.Framework
 * @subpackage Framework
 */
final class Framework
{
    /**
     * BEAR.Sunday root path
     *
     * @var system
     */
    public static $systemRoot;

    /**
     * BEAR.Sunday
     *
     * Framework version identification
     */
    const VERSION = '0.4.3';

    /**
     * Constructor
     */
    public function __construct()
    {
        // global setting
        self::$systemRoot = dirname(dirname(dirname(dirname(__DIR__))));
        umask(0);
    }

    /**
     *  Set auto loader
     *
     * @param string $namespace
     * @param string $appDir
     * @param array  $namespaces
     *
     * @return \BEAR\Sunday\Framework
     */
    public function setLoader($namespace, $appDir, array $namespaces = [])
    {
        static $loader;

        if (! is_null($loader)) {
            // unregister for another app
            spl_autoload_unregister([$loader, 'load']);
        }
        $system = self::$systemRoot;
        $packageDir = dirname(dirname($appDir));
        include_once $system . '/scripts/core_loader.php';
        include_once $packageDir . '/vendor/aura/autoload/src.php';
        $loader = new Loader;
        $loader->setMode(Loader::MODE_DEBUG);
        $autloadNamespaces = include $packageDir . '/vendor/composer/autoload_namespaces.php';
        $autloadNamespaces[$namespace] = dirname($appDir);
        $autloadNamespaces += $namespaces;
        $loader->setPaths($autloadNamespaces);
        $classes = include $packageDir . '/vendor/composer/autoload_classmap.php';
        $loader->setClasses($classes);
        $loader->register();
        AnnotationRegistry::registerAutoloadNamespace('Ray\Di\Di\\', $packageDir . '/vendor/ray/di/src/');
        AnnotationRegistry::registerAutoloadNamespace('BEAR\Resource\Annotation\\', $packageDir . '/vendor/bear/resource/src/');
        AnnotationRegistry::registerAutoloadNamespace('BEAR\Sunday\Annotation\\', $packageDir . '/src/');
        AnnotationRegistry::registerAutoloadNamespace($namespace . '\Annotation', dirname($appDir));

        return $this;
    }
}