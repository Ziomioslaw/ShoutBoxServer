<?php
require_once(__DIR__ . '/core/UnitTest.php');

$function = require_once(__DIR__ . '/../src/edit.shout.php');

class EditShoutsUnitTest extends UnitTest {
    public function __construct($name) {
        parent::__construct($name);
    }

    public function replace($search, $replace) {
        $this->application->setRequestData(array(
                'search' => $search,
                'replace' => $replace
            ));

        return $this;
    }

    public function expectedChange($shoutId, $expectedMessage) {
        $shout = $this->getShout(1);

        if ($shout['message'] === $expectedMessage) {
            $this->ok();
        } else {
            $this->fail($expectedMessage, $shout['message']);
        }

        return $this;
    }
}

(new EditShoutsUnitTest('Standard edition'))
    ->setMainTimer(-5 * 60)
    ->setActiveUser(SECOND_USER_ID)
    ->insertShout(SECOND_USER_ID, 'At vero eos et accusamus et iusto odio dignixsimos ducimus qui blanditiis')
    ->replace('dignixsimos', 'dignissimos')
    ->run($function, array(1))
    ->expectedChange(1, 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis');

(new EditShoutsUnitTest('Edition with non-asci characters'))
    ->setMainTimer(-5 * 60)
    ->setActiveUser(SECOND_USER_ID)
    ->insertShout(SECOND_USER_ID, 'At vero eos et ążźćńłó et iusto odio dignixsimos ducimus qui blanditiis')
    ->replace('ążźćńłó', 'ĄŻŹĆŃŁÓ')
    ->run($function, array(1))
    ->expectedChange(1, 'At vero eos et ĄŻŹĆŃŁÓ et iusto odio dignixsimos ducimus qui blanditiis');
