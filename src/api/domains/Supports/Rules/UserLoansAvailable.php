<?php

namespace Domains\Supports\Rules;

use Illuminate\Contracts\Validation\Rule;
use Domains\Supports\Models\UserLoanModel;
use DB;

class UserLoansAvailable implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return DB::table('user_loans')
            ->whereNull('deleted_at')
            ->where([
                'id'     => $value,
                'status' => UserLoanModel::STATUS_ACTIVE
            ])
            ->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This user loan is not available.';
    }
}