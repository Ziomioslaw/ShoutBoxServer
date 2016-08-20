<?php
class Forum
{
    private $db_prefix;
    private $application;

    public function __construct($application, $db_prefix) {
        $this->application = $application;
        $this->db_prefix = $db_prefix;
    }

    public function findTopicsOfForum($message) {
        $matches = null;
        preg_match_all('/(?<!\[url=)https?:\/\/www\.gimpuj\.info\/index.php\/topic,([0-9]+)\.((msg)?([0-9]+))?(\.new\.)?([^ ]+)/', $message, $matches);

        for($i = 0; $i < count($matches[0]); $i++) {
            $link = $matches[0][$i];
            $topicID = intval($matches[1][$i]);

            $sth = $this->application->db()->prepare("SELECT subject
                FROM {$this->db_prefix}messages
                WHERE ID_TOPIC = :topicID
                ORDER BY ID_MSG ASC");
            $sth->bindParam(':topicID', $topicID, PDO::PARAM_INT);
            $sth->execute();

            $value = $sth->fetch();

            if ($value !== false) {
                $message = str_replace($link, "[url=$link]{$value[0]}[/url]", $message);
            }
        }

        return $message;
    }
}
