<?php

namespace Domains\Supports\Models;

use Illuminate\Database\Eloquent\Model;

class UserLoanModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_loans';

    public const STATUS_ACTIVE   = 1;
    public const STATUS_INACTIVE = 0;

    public const PAYMENT_STATUS_PAYING = 1;
    public const PAYMENT_STATUS_PAID   = 2;
}
