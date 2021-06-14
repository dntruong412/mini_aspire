<?php

use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function() {
    Route::get('/',  'UserController@get')->name('backend.users.get');
    Route::post('/', 'UserController@create')->name('backend.users.create');
    Route::prefix('{userId}')
        ->middleware('check_user_exist')
        ->group(function() {
            Route::get('/', 'UserController@getById')->name('backend.users.get_by_id');
            Route::post('/', 'UserController@update')->name('backend.users.update');
            Route::delete('/', 'UserController@delete')->name('backend.users.delete');
            Route::post('loan', 'UserController@loan')->name('backend.users.loan');
            Route::get('loan', 'UserController@getUserLoans')->name('backend.users.get_user_loans');

            Route::prefix('/loan/{userLoanId}')
                ->middleware('check_user_loan_exist')
                ->group(function() {
                    Route::get('/', 'UserController@getUserLoanById')->name('backend.users.get_user_loan_by_id');
                    Route::post('repayments', 'UserController@repayments')->name('backend.users.loan_repayments');
                    Route::get('repayments', 'UserController@getUserLoanRepayments')->name('backend.users.get_loan_repayments');
            });
        });
});
