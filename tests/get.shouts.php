<?php
require_once(__DIR__ . '/../flight-master/flight/autoload.php');
require_once(__DIR__ . '/UserMock.php');
require_once(__DIR__ . '/build.database.php');

$function = require_once(__DIR__ . '/../src/get.shouts.php');

(new UnitTest('Normal user can delete only own shouts'))
    ->setMainTimer(-5 * 60)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(ADMIN_USER_ID)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(THIRD_USER_ID)

    ->setActiveUser(SECOND_USER_ID)
    ->run($function)
    ->expectedThatCanDelete(2);

(new UnitTest('Admin can delete any shout'))
    ->setMainTimer(-5 * 60)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(ADMIN_USER_ID)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(THIRD_USER_ID)

    ->setActiveUser(ADMIN_USER_ID)
    ->run($function)
    ->expectedThatCanDelete(4);

(new UnitTest('User can delete only latest shouts'))
    ->setMainTimer(-16 * 60 * 60)
    ->insertShout(FIRST_USER_ID)
    ->insertShout(FIRST_USER_ID)

    ->setMainTimer(-60 * 60)
    ->insertShout(FIRST_USER_ID)
    ->insertShout(FIRST_USER_ID)

    ->setActiveUser(FIRST_USER_ID)
    ->run($function)
    ->expectedThatCanDelete(2);

class UnitTest {
    private $name;
    private $application;
    private $result;

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

    public function expectedThatCanDelete($exptected) {
        $result = array_reduce($this->result, function ($carry, $item) {
            return $item['can_delete'] ? ($carry +1) : $carry;
        }, 0);

        if ($result !== $exptected) {
            echo "Unit test: {$this->name}\nExptected: {$exptected}\nRecived:   {$result}\n";
            throw new Exception('Unit test faild');
        } else {
            echo "[OK] Unit test: {$this->name}\n";
        }

        return $this;
    }

    private function buildApplication() {
        $application = new flight\Engine();
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
