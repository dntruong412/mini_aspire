<?php

namespace Domains\Backend\Controllers;

use Illuminate\Http\Request;
use Domains\Backend\Requests\User\Create as CreateRequest;
use Domains\Backend\Requests\User\Update as UpdateRequest;
use Domains\Backend\Requests\User\Loan as LoanRequest;
use Domains\Backend\Requests\User\Repayment as RepaymentRequest;
use Domains\Backend\Models\UserModel;
use Domains\Backend\Models\UserLoanModel;
use Domains\Backend\Models\UserLoanRepaymentModel;

class UserController extends Controller
{
    /**
     * @api {get} backend/users Get users
     * @apiName Get users
     * @apiGroup Backend-Users
     * @apiSampleRequest backend/users
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "current_page": 1,
     *     "data": [{
     *         "id":"1",
     *         "name":"Kozey and Sons_zh",
     *         "status": 1,
     *         "created_at": "2020-06-13 13:04:40",
     *         "updated_at": null
     *    }],
     *     "first_page_url": "http://localhost:10000/v1/backend/users?page=1",
     *     "from": 1,
     *     "last_page": 1,
     *     "last_page_url": "http://localhost:10000/v1/backend/users?page=1",
     *     "next_page_url": null,
     *     "path": "http://localhost:10000/v1/backend/users",
     *     "per_page": 10,
     *     "prev_page_url": null,
     *     "to": 5,
     *     "total": 5
     * }
     */
    public function get(UserModel $userModel) {
        $data = $userModel->getUsers();
        return array_merge(['status' => 1], $data->toArray());
    }

    /**
     * @api {get} backend/users/:user_id Get user by id
     * @apiName Get user by id
     * @apiGroup Backend-Users
     * @apiSampleRequest backend/users/:user_id
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "data": {
     *         "id":"1",
     *         "name":"Kozey and Sons_zh",
     *         "status": 1,
     *         "created_at": "2020-06-13 13:04:40",
     *         "updated_at": null
     *    }
     * }
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Bad request
     *     {
     *         "status": 0,
     *         "message": "User not found"
     *     }
     */
    public function getById(Request $request) {
        return [
            'status' => 1,
            'data'   => $request->user
        ];
    }

    /**
     * @api {post} backend/users Create user
     * @apiName Create user
     * @apiGroup Backend-Users
     * @apiParam {String} [name] Name.
     * @apiParam {Number} [status] Status.
     * @apiSampleRequest backend/users
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "message": "Successfully created",
     *     "data": {
     *         "id":"1db48456-6e22-45bf-816b-7983509d1eba",
     *         "name":"Kozey and Sons_zh",
     *         "status": 1,
     *         "created_at": "2020-06-13 13:04:40",
     *         "updated_at": null
     *    }
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function create(CreateRequest $request, UserModel $userModel) {
        $date = new \DateTime();

        $user = $request->only('name', 'status');
        $user = array_merge($user, [
            'created_at' => $date
        ]);

        $user = $userModel->createUser($user);

        return [
            'status'  => 1,
            'message' => 'Successfully created',
            'data'    => $user
        ];
    }

    /**
     * @api {post} backend/users/:user_id Update user
     * @apiName Update user
     * @apiGroup Backend-Users
     * @apiParam {String} [name] Name.
     * @apiParam {Number} [status] Status.
     * @apiSampleRequest backend/users/:user_id
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "message": "Successfully updated"
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function update(UpdateRequest $request, UserModel $userModel, $userId) {
        $date = new \DateTime();

        $user = $request->only('name', 'status');
        $user = array_merge($user, [
            'updated_at' => $date
        ]);

        $userModel->updateUser($userId, $user);

        return [
            'status'  => 1,
            'message' => 'Successfully updated'
        ];
    }

    /**
     * @api {delete} backend/users/:user_id Delete user
     * @apiName Delete user
     * @apiGroup Backend-Users
     * @apiSampleRequest backend/users/:user_id
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "message": "Successfully deleted"
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function delete(UserModel $userModel, $userId) {
        $userModel->deleteUser($userId);

        return [
            'status'  => 1,
            'message' => 'Successfully deleted'
        ];
    }

    /**
     * @api {post} backend/users/:user_id/loan Submit user loan
     * @apiName Submit user loan
     * @apiGroup Backend-Users
     * @apiParam {Number} [amount] Amount.
     * @apiParam {Number} [duration] Duration.
     * @apiParam {Number} [interest_rate] Interest rate.
     * @apiParam {Number} [arrangement_fee] Arrangement fee.
     * @apiParam {Number} [repayment_frequency] Repayment frequency.
     * @apiSampleRequest backend/users/:user_id/loan
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "message": "Successfully loaned"
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function loan(LoanRequest $request, $userId) {
        $loanInfo = $request->only('amount', 'duration', 'interest_rate', 'arrangement_fee', 'repayment_frequency');

        $userLoan = new \Domains\Backend\Services\UserLoan\UserLoan(
            new \Domains\Backend\Services\UserLoan\FixedInterestRateLoan($userId, $loanInfo)
        );
        $loanInfo = $userLoan->execute();

        return [
            'status'  => 1,
            'message' => 'Successfully loaned',
            'data'    => $loanInfo
        ];
    }

    /**
     * @api {get} backend/users/:user_id/loan Get user loans
     * @apiName Get user loans
     * @apiGroup Backend-Users
     * @apiSampleRequest backend/users/:user_id/loan
     * @apiSuccessExample Success-Response:
     * {
     *     "status":1,
     *     "current_page":1,
     *     "data":[{
     *         "id":"dc944bba-59a9-4f8c-846a-7bbf77b8e8da",
     *         "amount":1000000,
     *         "debt":500000,
     *         "duration":3,
     *         "repayment_frequency":3,
     *         "interest_rate":10,
     *         "arrangement_fee":100000,
     *         "payment_status":1,
     *         "created_at":"2021-06-12 02:42:06",
     *         "updated_at":"2021-06-12 03:05:02"
     *     }],
     *     "first_page_url":"http://localhost:10000/v1/backend/users/2/loan?page=1",
     *     "from":1,
     *     "last_page":1,
     *     "last_page_url":"http://localhost:10000/v1/backend/users/2/loan?page=1",
     *     "next_page_url":null,
     *     "path":"http://localhost:10000/v1/backend/users/2/loan",
     *     "per_page":15,
     *     "prev_page_url":null,
     *     "to":1,
     *     "total":1
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function getUserLoans(UserLoanModel $userLoanModel, $userId) {
        $result = $userLoanModel->getAllLoanInfo($userId);

        return array_merge([
            'status' => 1,
        ], $result->toArray());
    }

    /**
     * @api {get} backend/users/:user_id/loan/:user_loan_id Get user loan by id
     * @apiName Get user loan by id
     * @apiGroup Backend-Users
     * @apiSampleRequest backend/users/:user_id/loan/:user_loan_id
     * @apiSuccessExample Success-Response:
     * {
     *     "status":1,
     *     "data":{
     *         "id":"06820527-4b17-42da-bc00-2349a0ae366f",
     *         "amount":10000000,
     *         "debt":0,
     *         "duration":12,
     *         "repayment_frequency":12,
     *         "interest_rate":8,
     *         "arrangement_fee":10000,
     *         "payment_status":2,
     *         "created_at":"2021-06-11 03:40:33",
     *         "updated_at":"2021-06-12 01:18:41"
     *     }
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function getUserLoanById(Request $request) {
        return [
            'status' => 1,
            'data'   => $request->loan
        ];
    }

    /**
     * @api {post} backend/users/:user_id/loan/:user_loan_id/repayments Submit user repayment
     * @apiName Submit user repayment
     * @apiGroup Backend-Users
     * @apiParam {Number} [amount] Amount.
     * @apiSampleRequest backend/users/:user_id/loan/:user_loan_id/repayments
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "message": "Successfully paid"
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function repayments(RepaymentRequest $request, $userId, $userLoanId) {
        $userLoan = new \Domains\Backend\Services\UserLoan\UserLoan(
            new \Domains\Backend\Services\UserLoan\FixedInterestRateRepayment(
                $userLoanId, 
                $request->input('amount')
            )
        );
        $result = $userLoan->execute();

        return [
            'status'  => 1,
            'message' => 'Successfully repaid',
            'data'    => $result
        ];
    }

    /**
     * @api {get} backend/users/:user_id/loan/:user_loan_id/repayments Get user loan by id
     * @apiName Get user loan repayments by id
     * @apiGroup Backend-Users
     * @apiSampleRequest backend/users/:user_id/loan/:user_loan_id/repayments
     * @apiSuccessExample Success-Response:
     * {
     *     "status": 1,
     *     "message": "Successfully paid"
     * }
     * @apiError (Error 5xx) DBException Unexpected database error.
     */
    public function getUserLoanRepayments(UserLoanRepaymentModel $userLoanModel, $userId, $userLoanId) {
        $loanInfo = $userLoanModel->getRepaymentHistory($userLoanId);

        return [
            'status' => 1,
            'data'   => $loanInfo
        ];
    }
}
