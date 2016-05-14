<?php
class User {
    private $data;

    public function __construct(array $context) {
        $this->data = $context['user'];
    }

    public function isAdmin() {
        return $this->data['is_admin'];
    }

    public function getID() {
        return $this->data['id'];
    }

    public function getName() {
        return $this->data['name']; // it can be also 'username'
    }
}
