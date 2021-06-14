<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserLoanTest extends TestCase
{
    public const TEST_USER_ID = 1;

    /**
     * test make a user loan
     *
     * @group UserLoan
     * @return void
     */
    public function testMakeUserLoan()
    {
        $response = $this->post(route('backend.users.loan', [
            'userId' => self::TEST_USER_ID
        ]), [
            'amount'              => 10000000,
            'duration'            => 12,
            'interest_rate'       => 8,
            'arrangement_fee'     => 10000,
            'repayment_frequency' => 12
        ]);

        $response->assertStatus(200);

        return $response;
    }

    /**
     * test get all user loans
     *
     * @group UserLoan
     * @return void
     */
    public function testGetUserLoans()
    {
        $response = $this->get(route('backend.users.get_user_loans', [
            'userId' => self::TEST_USER_ID
        ]));
        $response->assertStatus(200);

        $response = $this->get(route('backend.users.get_user_loans', [
            'userId' => 1234
        ]));
        $response->assertStatus(404);
    }

    /**
     * test get a user loan
     *
     * @group UserLoan
     * @depends testMakeUserLoan
     * @return void
     */
    public function testGetUserLoan($userLoanResponse)
    {
        $response = $this->get(route('backend.users.get_user_loan_by_id', [
            'userId'     => self::TEST_USER_ID,
            'userLoanId' => $userLoanResponse->json('data.id')
        ]));
        $response->assertStatus(200);
    }

    /**
     * test do repayment for a user loan
     *
     * @group UserLoan
     * @depends testMakeUserLoan
     * @return void
     */
    public function testRepaymentUserLoan($userLoanResponse)
    {
        $response = $this->post(route('backend.users.loan_repayments', [
            'userId'     => self::TEST_USER_ID,
            'userLoanId' => $userLoanResponse->json('data.id')
        ]), [
            'amount' => 1000000
        ]);
        $response->assertStatus(200);
    }

    /**
     * test get all user loan repayments
     *
     * @group UserLoan
     * @depends testMakeUserLoan
     * @return void
     */
    public function testGetUserLoanRepayment($userLoanResponse)
    {
        $response = $this->get(route('backend.users.get_loan_repayments', [
            'userId'     => self::TEST_USER_ID,
            'userLoanId' => $userLoanResponse->json('data.id')
        ]));
        $response->assertStatus(200);
    }
}
