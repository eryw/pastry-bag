<?php
namespace PastryBag\Test\TestCase\Di;

use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;
use Cake\Network\Socket;
use Cake\TestSuite\TestCase;
use PastryBag\Di\PastryBag;

class DummyConfig1 implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        // TODO: Implement define() method.
    }

    public function modify(Container $di)
    {
        // TODO: Implement modify() method.
    }
}

class DummyConfig2 implements ContainerConfigInterface
{
    public function define(Container $di)
    {
        // TODO: Implement define() method.
    }

    public function modify(Container $di)
    {
        // TODO: Implement modify() method.
    }
}

class DummyConfig3
{
}

class DummyConfig4
{
    protected $dt;

    protected $dc;

    public function __construct(Socket $dt, DummyConfig2 $dc)
    {
        $this->dt = $dt;
        $this->dc = $dc;
    }

    public function getDt()
    {
        return $this->dt;
    }

    public function getDc()
    {
        return $this->dc;
    }
}

class PastryBagTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_aura_di_container()
    {
        $di = PastryBag::create();
        $this->assertInstanceOf(Container::class, $di);
    }

    /**
     * @test
     */
    public function it_accept_array_of_aura_container_config_as_parameter()
    {
        $param = [
            new DummyConfig1(),
            '\PastryBag\Test\TestCase\Di\DummyConfig2'
        ];
        $di = PastryBag::create($param);
        $this->assertInstanceOf(Container::class, $di);
    }

    /**
     * @test
     */
    public function it_throw_an_exception_if_unexpected_parameter_has_been_passed()
    {
        $param = [
            new DummyConfig1(),
            '\PastryBag\Test\TestCase\Di\DummyConfig3'
        ];
        $this->expectException(\InvalidArgumentException::class);
        PastryBag::create($param);
    }

    /**
     * @test
     */
    public function it_will_auto_resolve_the_constructor_dependecies()
    {
        $di = PastryBag::getContainer();
        $obj = $di->newInstance(DummyConfig4::class);
        $this->assertInstanceOf(Socket::class, $obj->getDt());
        $this->assertInstanceOf(DummyConfig2::class, $obj->getDc());
    }
}
