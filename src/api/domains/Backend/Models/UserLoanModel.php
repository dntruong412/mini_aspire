<?php

namespace Domains\Backend\Models;

use Domains\Supports\Exceptions\DBException;
use Domains\Supports\Models\UserLoanModel as CommonUserLoanModel;
use DB;
use Exception;

class UserLoanModel extends CommonUserLoanModel
{
    /**
     * get user loan
     * 
     * @param  Array $loanInfo
     * @return Object
     */
    public function getLoanInfo($loanId) {
        return DB::table($this->table)
            ->where('status', self::STATUS_ACTIVE)
            ->whereNull('deleted_at')
            ->where('id', $loanId)
            ->select('id', 'amount', 'debt', 'duration', 'repayment_frequency', 'interest_rate', 'arrangement_fee', 'payment_status', 'created_at', 'updated_at')
            ->first();
    }

    /**
     * get user loans
     * 
     * @param  Int $userId
     * @return Object
     */
    public function getAllLoanInfo($userId) {
        return DB::table($this->table)
            ->where('status', self::STATUS_ACTIVE)
            ->whereNull('deleted_at')
            ->where('user_id', $userId)
            ->select('id', 'amount', 'debt', 'duration', 'repayment_frequency', 'interest_rate', 'arrangement_fee', 'payment_status', 'created_at', 'updated_at')
            ->paginate();
    }

    /**
     * submit user loan
     * 
     * @param  Array $loanInfo
     * @return Boolean
     */
    public function submitLoan($loanInfo) {
        try {
            DB::beginTransaction();
            $loanInfo['id'] = \Domains\Supports\UUID::generate($this->table);
            DB::table($this->table)->insert($loanInfo);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new DBException($e);
        }
        return $loanInfo;
    }

    /**
     * update user loan
     * 
     * @param  Array $loanInfo
     * @return Boolean
     */
    public function updateLoan($loanId, $loanInfo) {
        DB::table($this->table)->where('id', $loanId)->update($loanInfo);
    }
}
