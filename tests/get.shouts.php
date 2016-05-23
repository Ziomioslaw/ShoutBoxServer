<?php
require_once(__DIR__ . '/UnitTest.php');

$function = require_once(__DIR__ . '/../src/get.shouts.php');

class GetShoutsUnitTest extends UnitTest {
    public function __construct($name) {
        parent::__construct($name);
    }

    public function expectedThatCanDelete($exptected) {
        $result = array_reduce($this->result, function ($carry, $item) {
            return $item['can_delete'] ? ($carry +1) : $carry;
        }, 0);

        if ($result !== $exptected) {
            $this->fail($exptected, $result);
        } else {
            $this->ok();
        }

        return $this;
    }
}

(new GetShoutsUnitTest('Normal user can delete only own shouts'))
    ->setMainTimer(-5 * 60)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(ADMIN_USER_ID)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(THIRD_USER_ID)

    ->setActiveUser(SECOND_USER_ID)
    ->run($function)
    ->expectedThatCanDelete(2);

(new GetShoutsUnitTest('Admin can delete any shout'))
    ->setMainTimer(-5 * 60)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(ADMIN_USER_ID)
    ->insertShout(SECOND_USER_ID)
    ->insertShout(THIRD_USER_ID)

    ->setActiveUser(ADMIN_USER_ID)
    ->run($function)
    ->expectedThatCanDelete(4);

(new GetShoutsUnitTest('User can delete only latest shouts'))
    ->setMainTimer(-16 * 60 * 60)
    ->insertShout(FIRST_USER_ID)
    ->insertShout(FIRST_USER_ID)

    ->setMainTimer(-60 * 60)
    ->insertShout(FIRST_USER_ID)
    ->insertShout(FIRST_USER_ID)

    ->setActiveUser(FIRST_USER_ID)
    ->run($function)
    ->expectedThatCanDelete(2);
