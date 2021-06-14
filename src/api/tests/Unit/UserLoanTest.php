<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserLoanTest extends TestCase
{
    /**
     * test user loan.
     *
     * @return void
     */
    public function testUserLoan()
    {
        $userId = 1;

        $anotherInterestRate = $this->createMock(\Domains\Backend\Services\UserLoan\Action::class);
        $anotherInterestRate->method('execute')
            ->willReturn([
                'id'      => 1,
                'user_id' => $userId
            ]);

        $loanInfo = \Domains\Backend\Services\UserLoan\UserLoan::execute($anotherInterestRate);

        $this->assertArrayHasKey('id', $loanInfo);
        $this->assertTrue($loanInfo['user_id'] == $userId);
    }

    /**
     * test user loan repayment.
     *
     * @return void
     */
    public function testUserRepayment()
    {
        $userLoanId = '05534a99-5072-471e-807c-74ba7a2bde8e';

        $repaymentMockObj = $this->createMock(\Domains\Backend\Services\UserLoan\Action::class);
        $repaymentMockObj->method('execute')
            ->willReturn([
                'id' => $userLoanId,
            ]);

        $repaymentInfo = \Domains\Backend\Services\UserLoan\UserLoan::execute($repaymentMockObj);

        $this->assertArrayHasKey('id', $repaymentInfo);
        $this->assertTrue($repaymentInfo['id'] == $userLoanId);
    }
}
