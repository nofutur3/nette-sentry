<?php


class UnitCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests

    public function testThatItCapturesException(UnitTester $I)
    {
        $exc = new \Exception();
        $mockEvent = $I->getMockBuilder('')->disableOriginalConstructor()->getMock();
        $mockEvent
            ->method('getException')
            ->willReturn($exc);
    }

    public function testUserContext(UnitTester $I)
    {
    }
}
