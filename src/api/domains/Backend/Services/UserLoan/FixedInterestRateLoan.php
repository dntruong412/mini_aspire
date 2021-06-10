<?php

namespace Domains\Backend\Services\UserLoan;

use Domains\Backend\Models\UserLoanModel;

class FixedInterestRateLoan implements Action {

    private $userID;
    private $loanInfo;

    public function __construct($userID, $loanInfo) {
        $this->userID = $userID;
        $this->loanInfo = $loanInfo;
    }

    public function execute() {
        $userLoanModel = new UserLoanModel();

        $this->loanInfo = array_merge([
            'id'      => null,
            'user_id' => $this->userID
        ], $this->loanInfo, [
            'debt'       => $this->loanInfo['amount'] * (1 + $this->loanInfo['interest_rate'] / 100),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $userLoanModel->submitLoan($this->loanInfo);
    }

}