<?php

namespace Domains\Backend\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Domains\Supports\Exceptions\ValidationException;

class Loan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'              => 'bail|required|int|min:1',
            'duration'            => 'bail|required|int|min:1',
            'interest_rate'       => 'bail|required|int|min:0',
            'repayment_frequency' => 'bail|required|int|min:0',
            'arrangement_fee'     => 'bail|required|int|min:0'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws Domains\Supports\Exceptions\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }
}
