<?php
class UserMock {
    private $user;
    private $userId;

    public function setActiveUser(array $user) {
        $this->user = $user;
    }

    public function getId() {
        return $this->user['id'];
    }

    public function isAdmin() {
        return $this->user['isAdmin'];
    }
}
