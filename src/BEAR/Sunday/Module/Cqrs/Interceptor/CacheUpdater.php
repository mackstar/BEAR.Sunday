<?php
/**
 * This file is part of the BEAR.Sunday package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace BEAR\Sunday\Module\Cqrs\Interceptor;

use Guzzle\Cache\CacheAdapterInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;
use ReflectionMethod;

/**
 * Cache update interceptor
 */
class CacheUpdater implements MethodInterceptor
{
    use EtagTrait;

    /**
     * Constructor
     *
     * @param CacheAdapterInterface $cache
     *
     * @Inject
     * @Named("resource_cache")
     */
    public function __construct(CacheAdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function invoke(MethodInvocation $invocation)
    {
        $ro = $invocation->getThis();

        // onGet(void) clear cache
        $id = $this->getEtag($ro, [0 => null]);
        $this->cache->delete($id);

        // onGet($id, $x, $y...) clear cache
        $getMethod = new ReflectionMethod($ro, 'onGet');
        $parameterNum = count($getMethod->getParameters());
        // cut as same size and order as onGet
        $slicedInvocationArgs = array_slice($invocation->getArguments(), 0, $parameterNum);
        $id = $this->getEtag($ro, $slicedInvocationArgs);
        $this->cache->delete($id);

        return $invocation->proceed();
    }
}
