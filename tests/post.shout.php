<?php
require_once(__DIR__ . '/core/UnitTest.php');

$function = require_once(__DIR__ . '/../src/post.shout.php');

class PostUnitTest extends UnitTest {
    private $message;

    public function __construct($name) {
        parent::__construct($name);
    }

    public function send($message) {
        $this->message = $message;
        $this->application->setRequestData(array(
            'message' => $message
        ));

        return $this;
    }

    public function exaclyTheSame() {
        $result = $this->getLastAddedShout();
        if (!$result) {
            throw new Exception('There is no shouts in the database');
        }

        $resultMessage = $result['message'];
        if ($resultMessage !== $this->message) {
            $this->fail($this->message, $resultMessage);
        } else {
            $this->ok();
        }

        return $this;
    }

    private function getLastAddedShout() {
        $shoutBoxTableName = $this->application->get('tableName');

        $stmt = $this->application->db()->query("SELECT *
                FROM {$shoutBoxTableName}
                ORDER BY ID_SHOUT DESC
                LIMIT 1",
            PDO::FETCH_ASSOC);

        return $stmt->fetch();
    }
}

(new PostUnitTest('Post normal message'))
    ->setActiveUser(ADMIN_USER_ID)
    ->send('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum')
    ->run($function)
    ->exaclyTheSame();
