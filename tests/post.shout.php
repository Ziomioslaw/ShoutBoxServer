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

    public function exactlyTheSame() {
        return $this->compearMessage($this->message);
    }

    public function exactlyAs($expected) {
        return $this->compearMessage($expected);
    }

    private function compearMessage($expected) {
        $result = $this->getLastAddedShout();
        if (!$result) {
            throw new Exception('There is no shouts in the database');
        }

        $resultMessage = $result['message'];
        if ($resultMessage !== $expected) {
            $this->fail($expected, $resultMessage);
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
    ->exactlyTheSame();

(new PostUnitTest('Post message with apostrophe or quotation mark'))
    ->setActiveUser(ADMIN_USER_ID)
    ->send('Excepteur sint \'occaecat" cupidatat non proident", sunt in culpa qui officia deserunt mollit anim\' id est laborum')
    ->run($function)
    ->exactlyAs('Excepteur sint \'occaecat&quot; cupidatat non proident&quot;, sunt in culpa qui officia deserunt mollit anim\' id est laborum');

(new PostUnitTest('Post message with HTML encoded characters'))
    ->setActiveUser(ADMIN_USER_ID)
    ->send('<hug>')
    ->run($function)
    ->exactlyAs('&lt;hug&gt;');
