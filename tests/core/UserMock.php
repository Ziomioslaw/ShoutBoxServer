<?php
class UserMock {
    private $user = null;
    private $userId;

    public function setActiveUser(array $user) {
        $this->user = $user;
    }

    public function getId() {
        return $this->getUserData('id');
    }

    public function getName() {
        return $this->getUserData('name');
    }

    public function isAdmin() {
        return $this->getUserData('isAdmin');
    }

    private function getUserData($field) {
        if ($this->user !== null) {
            return $this->user[$field];
        }

        throw new Exception('User not set!');
    }
}
