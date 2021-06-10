<?php

namespace Domains\Supports\Rules;

use Illuminate\Contracts\Validation\Rule;
use Domains\Supports\Models\UserModel;
use DB;

class UserAvailable implements Rule
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
        return DB::table('users')
            ->whereNull('deleted_at')
            ->where([
                'id'     => $value,
                'status' => UserModel::STATUS_ACTIVE
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
        return 'This user is not available.';
    }
}