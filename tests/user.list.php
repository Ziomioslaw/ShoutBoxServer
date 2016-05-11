<?php
define('ADMIN_USER_ID', 1);
define('FIRST_USER_ID', 2);
define('SECOND_USER_ID', 3);
define('THIRD_USER_ID', 4);

return array(
    '1' => array(
        'id' => ADMIN_USER_ID,
        'name' => 'Admin',
        'email' => 'admin@email.com',
        'isAdmin' => true
    ),
    '2' => array(
        'id' => FIRST_USER_ID,
        'name' => 'FirstUser',
        'email' => 'firstuser@email.com',
        'isAdmin' => false
    ),
    '3' => array(
        'id' => SECOND_USER_ID,
        'name' => 'SecondUser',
        'email' => 'seconduser@email.com',
        'isAdmin' => false
    ),
    '4' => array(
        'id' => THIRD_USER_ID,
        'name' => 'ThirdUser',
        'email' => 'thirduser@email.com',
        'isAdmin' => false
    )
);
