<?php
require_once(__DIR__ . '/../flight-master/flight/autoload.php');
use flight\Engine;

class EngineMock extends Engine {
    private $request;

    public function request() {
        return $this->request;
    }

    public function mockSetRequest(RequestMock $mock) {
        $this->request = $mock;
    }
}

class RequestMock {

}
