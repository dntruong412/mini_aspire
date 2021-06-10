<?php

namespace Domains\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Domains\Supports\Exceptions\DBException;
use DB;
use Exception;

class UserLoanRepaymentModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_loan_repayments';

    public const STATUS_ACTIVE   = 1;
    public const STATUS_INACTIVE = 0;

    /**
     * submit user loan
     * 
     * @param  Array $loanInfo
     * @return Boolean
     */
    public function getRepaymentHistory($loanId) {
        return DB::table($this->table)
            ->where('status', self::STATUS_ACTIVE)
            ->whereNull('deleted_at')
            ->where('user_loan_id', $loanId)
            ->select('id', 'amount', 'created_at', 'updated_at')
            ->get()
            ->toArray();
    }

    /**
     * submit user loan
     * 
     * @param  Array $repaymentInfo
     * @return Boolean
     */
    public function submitRepayment($repaymentInfo, $userLoanId, $userLoanInfo) {
        try {
            DB::beginTransaction();
            DB::table($this->table)->insert($repaymentInfo);
            (new UserLoanModel())->updateLoan($userLoanId, $userLoanInfo);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new DBException($e);
        }
        return $repaymentInfo;
    }
}
