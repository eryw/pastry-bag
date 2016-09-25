<?php
namespace PastryBag\Di;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;

class PastryBag
{
    /**
     * The current container instance
     *
     * @var \Aura\Di\Container
     */
    protected static $instance;

    /**
     * @param array $configClasses
     * @return \Aura\Di\Container
     */
    public static function create(array $configClasses = [])
    {
        $builder = new ContainerBuilder();
        $di = $builder->newConfiguredInstance($configClasses, $builder::AUTO_RESOLVE);

        return $di;
    }

    /**
     * Set the container object
     *
     * @param \Aura\Di\Container $container
     */
    public static function setContainer(Container $container)
    {
        static::$instance = $container;
    }

    /**
     * Get the container object
     *
     * @return \Aura\Di\Container
     */
    public static function getContainer()
    {
        return static::$instance;
    }
}
