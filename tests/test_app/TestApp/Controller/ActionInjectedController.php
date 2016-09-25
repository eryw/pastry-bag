<?php
namespace TestApp\Controller;

use Cake\Chronos\Chronos;
use Cake\Network\Socket;
use PastryBag\Controller\Controller;

class ActionInjectedController extends Controller
{
    public $chronos;

    public $socket;

    public $currentParam;

    public function action1($id, Chronos $chronos)
    {
        $this->currentParam = $id;
        $this->chronos = $chronos;
    }

    public function action2(Chronos $chronos, $id)
    {
        $this->chronos = $chronos;
        $this->currentParam = $id;
    }

    public function action3(Socket $socket, Chronos $chronos)
    {
        $this->chronos = $chronos;
        $this->socket = $socket;
    }
}