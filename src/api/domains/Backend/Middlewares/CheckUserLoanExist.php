<?php

namespace Domains\Backend\Middlewares;

use Closure;

class CheckUserLoanExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  String  $scope
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $userLoanModel = new \Domains\Backend\Models\UserLoanModel();
        $loan = $userLoanModel->getLoanInfo($request->route('userLoanId'));
        if (empty($loan)) {
            throw new \Domains\Supports\Exceptions\NotFoundException('Loan not found');
        }

        $request->merge([
            'loan' => $loan
        ]);

        return $next($request);
    }
}
