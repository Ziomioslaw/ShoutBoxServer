<?php
require_once(__DIR__ . '/../flight-master/flight/autoload.php');
require_once(__DIR__ . '/UserMock.php');
require_once(__DIR__ . '/UnitTest.php');
require_once(__DIR__ . '/build.database.php');

$function = require_once(__DIR__ . '/../src/post.shout.php');

(new UnitTest('Post normal message'))
    ->send('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum')
    ->run($function)
    ->exaclyTheSame();

class PostUnitTest {
    public function __construct($name) {
        parent::__construct($name);
    }

    public function send($message) {
        return $this;
    }

    public function exaclyTheSame() {
        return $this;
    }
}