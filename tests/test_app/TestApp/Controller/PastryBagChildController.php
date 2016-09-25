<?php
namespace TestApp\Controller;

use Cake\Network\Socket;
use PastryBag\Controller\Controller;

class PastryBagChildController extends Controller
{
    public $socket;

    public function __construct(Socket $socket)
    {
        $this->socket = $socket;
    }

    public function index()
    {
        $this->response->body('Hello Jane');

        return $this->response;
    }
}