<?php
namespace PastryBag\Test\TestCase\Controller;

use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Socket;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use PastryBag\Http\ControllerFactory;
use TestApp\Controller\ActionInjectedController;

class ControllerTest extends TestCase
{
    /**
     * reset environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Configure::write('App.namespace', 'TestApp');
        Router::reload();
    }

    /**
     * Test inject the controller constructor dependency
     *
     * @return void
     */
    public function testControllerConstructorInjection()
    {
        $request = new Request([
            'url' => 'pastry_bag_child/index',
            'params' => [
                'controller' => 'PastryBagChild',
                'action' => 'index',
            ]
        ]);
        $response = $this->getMockBuilder('Cake\Network\Response')->getMock();
        $result = (new ControllerFactory())->create($request, $response);
        $this->assertInstanceOf(Socket::class, $result->socket);
    }

    /**
     * test action dependency injection form1
     *
     * @return void
     */
    public function testControllerActionDIform1()
    {
        $request = new Request([
            'url' => 'action_injected/action1/12',
            'params' => [
                'controller' => 'ActionInjected',
                'action' => 'action1',
                'plugin' => false,
                'pass' => [
                    '12',
                    '22',
                ]
            ]
        ]);
        $response = $this->getMockBuilder('Cake\Network\Response')->getMock();
        $Controller = new ActionInjectedController();
        $Controller->construct($request, $response);
        $Controller->invokeAction();
        $Controller->construct($request, $response);

        $this->assertEquals(12, $Controller->currentParam);
        $this->assertInstanceOf(Chronos::class, $Controller->chronos);
    }

    /**
     * test action dependency injection form2
     *
     * @return void
     */
    public function testControllerActionDIform2()
    {
        $request = new Request([
            'url' => 'action_injected/action2/22',
            'params' => [
                'controller' => 'ActionInjected',
                'action' => 'action2',
                'plugin' => false,
                'pass' => [
                    '22',
                    '12',
                ]
            ]
        ]);
        $response = $this->getMockBuilder('Cake\Network\Response')->getMock();
        $Controller = new ActionInjectedController();
        $Controller->construct($request, $response);
        $Controller->invokeAction();
        $Controller->construct($request, $response);

        $this->assertEquals(22, $Controller->currentParam);
        $this->assertInstanceOf(Chronos::class, $Controller->chronos);
    }

    /**
     * test action dependency injection form3
     *
     * @return void
     */
    public function testControllerActionDIform3()
    {
        $request = new Request([
            'url' => 'action_injected/action3/12',
            'params' => [
                'controller' => 'ActionInjected',
                'action' => 'action3',
                'plugin' => false,
                'pass' => [
                    '12',
                    '22',
                ]
            ]
        ]);
        $response = $this->getMockBuilder('Cake\Network\Response')->getMock();
        $Controller = new ActionInjectedController();
        $Controller->construct($request, $response);
        $Controller->invokeAction();
        $Controller->construct($request, $response);

        $this->assertInstanceOf(Chronos::class, $Controller->chronos);
        $this->assertInstanceOf(Socket::class, $Controller->socket);
    }
}
