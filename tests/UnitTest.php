<?php
require_once(__DIR__ . '/../flight-master/flight/autoload.php');
require_once(__DIR__ . '/UserMock.php');
require_once(__DIR__ . '/EngineMock.php');

class UnitTest {
    private $name;
    protected $application;
    protected $result;

    public function __construct($name) {
        $this->name = $name;
        $this->application = $this->buildApplication();
        buildDB($this->application);
    }

    public function setMainTimer($add) {
        setMainTimer($this->application->time() + $add);
        return $this;
    }

    public function insertShout($memberId) {
        insertIntoShoutbox($this->application, $memberId);
        return $this;
    }

    public function setActiveUser($memberId) {
        global $userList;

        $this->application->user()->setActiveUser($userList[$memberId]);
        return $this;
    }

    public function run($function) {
        $this->result = $function($this->application);
        return $this;
    }

    private function buildApplication() {
        $application = new EngineMock();
        $application->register('user', 'UserMock');
        $application->map('error', function(Exception $ex) use($application) {
            $application->_error($ex);
        });
        $application->map('time', function() {
            return 100000;
        });

        return $application;
    }
}