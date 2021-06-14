<?php

namespace Domains\Backend\Services\UserLoan;

use Domains\Supports\Exceptions\ActionFailedException;
use Domains\Backend\Models\UserLoanModel;
use Domains\Backend\Models\UserLoanRepaymentModel;

class FixedInterestRateRepayment implements Action {

    private $userLoanId;
    private $repaymentAmount;

    public function __construct($userLoanId, $repaymentAmount) {
        $this->userLoanId = $userLoanId;
        $this->repaymentAmount = $repaymentAmount;
    }

    public function execute() {
        $userLoanModel          = new UserLoanModel();
        $userLoanRepaymentModel = new UserLoanRepaymentModel();

        $loanInfo = $userLoanModel->getLoanInfo($this->userLoanId);

        // Check if already repaid
        if ($loanInfo->payment_status == UserLoanModel::PAYMENT_STATUS_PAID) {
            throw new ActionFailedException('Already repaid');
        }

        // Check repayment amount min
        $repaymentInterest = $loanInfo->amount * (($loanInfo->interest_rate / 100) / $loanInfo->duration);
        $regularMinRepaymentAmount = $repaymentInterest + ($loanInfo->amount / $loanInfo->duration);
        if ($loanInfo->debt < $regularMinRepaymentAmount) {
            if ($this->repaymentAmount < $loanInfo->debt) {
                throw new ActionFailedException('Repayment amount must be equal to ' . number_format($loanInfo->debt, 0, '.', ','));
            }
        } else {
            if($this->repaymentAmount < $regularMinRepaymentAmount) {
                throw new ActionFailedException('Repayment amount must be at least ' . number_format($regularMinRepaymentAmount, 0, '.', ','));
            }
        }

        if($this->repaymentAmount > $loanInfo->debt) {
            $this->repaymentAmount = $loanInfo->debt;
        }

        $loanInfo->debt = $loanInfo->debt - $this->repaymentAmount;
        if ($loanInfo->debt <= 0) {
            $loanInfo->payment_status = UserLoanModel::PAYMENT_STATUS_PAID;
            $loanInfo->debt = 0;
        }

        $date = date('Y-m-d H:i:s');

        $repaymentInfo = [
            'user_loan_id' => $this->userLoanId,
            'amount'       => $this->repaymentAmount,
            'created_at'   => $date
        ];
        $updatedLoanInfo = [
            'debt'           => $loanInfo->debt,
            'payment_status' => $loanInfo->payment_status,
            'updated_at'     => $date
        ];

        $userLoanRepaymentModel->submitRepayment($repaymentInfo, $this->userLoanId, $updatedLoanInfo);

        unset($repaymentInfo['user_loan_id']);

        return array_merge($repaymentInfo, (array)$loanInfo, $updatedLoanInfo);
    }
}