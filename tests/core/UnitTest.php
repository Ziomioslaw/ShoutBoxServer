<?php
require_once(__DIR__ . '/UserMock.php');
require_once(__DIR__ . '/EngineMock.php');
require_once(__DIR__ . '/../builds/build.database.php');

define('ALLOW_TO_DELETE_TIME', 18000);

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

    public function insertShout($memberId, $message = null) {
        $values = array();
        if ($message !== null) {
            $values['message'] = $message;
        }

        insertIntoShoutbox($this->application, $memberId, $values);
        return $this;
    }

    public function setActiveUser($memberId) {
        global $userList;

        $this->application->user()->setActiveUser($userList[$memberId]);
        return $this;
    }

    public function run($function, array $arguments = array()) {
        $this->result = $function($this->application, $arguments);
        return $this;
    }

    public function fail($exptected, $result) {
        echo "Unit test: {$this->name}\nExptected: {$exptected}\nRecived:   {$result}\n";
        throw new Exception('Unit test faild');
    }

    public function ok() {
        echo "[OK] Unit test: {$this->name}\n";
    }

    protected function getShout($shoutId) {
        $shoutBoxTableName = $this->application->get('tableName');

        $sth = $this->application->db()->prepare("SELECT *
            FROM $shoutBoxTableName
            WHERE `ID_SHOUT` = $shoutId");

        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
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
        $application->map('buildDeleteSQL', function(UserMock $user) use($application) {
            return $user->isAdmin()
                ? '1'
                : '`ID_MEMBER` = ' . $user->getId() . ' AND `time` > ' . ($application->time() - ALLOW_TO_DELETE_TIME);
        });

        return $application;
    }
}
