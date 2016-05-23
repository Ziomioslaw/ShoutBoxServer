<?php
require_once(__DIR__ . '/../../flight-master/flight/autoload.php');
use flight\Engine;

class EngineMock extends Engine {
    private $request;

    public function request() {
        return $this->request;
    }

    public function setRequestData(array $data) {
        $this->request = new RequestMock();
        $this->request->data = new RequestMockData($data);
    }
}

class RequestMock {
    public $data = null;
}

class RequestMockData {
    private $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function __get($name) {
        return $this->data[$name];
    }
}
