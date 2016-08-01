<?php
namespace PastryBag\Di;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use Aura\Di\ContainerConfigInterface;

class PastryBag
{

    /**
     * The current container instance
     * @var Container
     */
    protected static $instance;

    /**
     * @param array $configClasses
     * @return Container
     */
    public static function create(array $configClasses = [])
    {
        $builder = new ContainerBuilder();
        $di = $builder->newInstance($builder::AUTO_RESOLVE);

        foreach ($configClasses as $configClass) {
            /** @var ContainerConfigInterface $config */
            $config = static::getConfig($configClass);
            $config->define($di);
        }

        return $di;
    }

    /**
     * @param Container|null $instance
     * @return Container
     */
    public static function container(Container $instance = null)
    {
        if ($instance !== null) {
            static::$instance = $instance;
        }

        return static::$instance;
    }

    /**
     *
     * Get config object from connfig class or return the object
     *
     * @param mixed $config name of class to instantiate
     *
     * @return Object
     * @throws \InvalidArgumentException if invalid config
     *
     * @access protected
     */
    protected static function getConfig($config)
    {
        if (is_string($config)) {
            $config = new $config;
        }

        if (!$config instanceof ContainerConfigInterface) {
            throw new \InvalidArgumentException(
                'Container configs must implement ContainerConfigInterface'
            );
        }

        return $config;
    }
}
