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
        $loanInfo = [
            'amount'              => 10000000,
            'interest_rate'       => 10,
            'arrangement_fee'     => 10000,
            'repayment_frequency' => 10
        ];

        $anotherInterestRate = $this->getMockBuilder(\Domains\Backend\Services\UserLoan\FixedInterestRateRepayment::class)
            ->setConstructorArgs([$userId, $loanInfo])
            ->getMock();
        $anotherInterestRate->method('execute')
            ->willReturn(array_merge([
                'id'      => rand(),
                'user_id' => $userId
            ], $loanInfo));

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
        $repaymentAmount = 1000000;

        $repaymentMockObj = $this->getMockBuilder(\Domains\Backend\Services\UserLoan\FixedInterestRateRepayment::class)
            ->setConstructorArgs([$userLoanId, $repaymentAmount])
            ->getMock();
        $repaymentMockObj->method('execute')
            ->willReturn([
                'id' => $userLoanId,
            ]);

        $repaymentInfo = \Domains\Backend\Services\UserLoan\UserLoan::execute($repaymentMockObj);

        $this->assertArrayHasKey('id', $repaymentInfo);
        $this->assertTrue($repaymentInfo['id'] == $userLoanId);
    }
}
