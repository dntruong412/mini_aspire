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
            Route::post('/loan', 'UserController@loan')->name('backend.users.loan');
            Route::post('/pay', 'UserController@pay')->name('backend.users.pay');
            Route::get('/loan', 'UserController@getUserLoans')->name('backend.users.get_user_loans');
            Route::get('/loan/{userLoanId}', 'UserController@getUserLoanById')->name('backend.users.get_user_loan_by_id');
            Route::get('/loan/{userLoanId}/repayments', 'UserController@getUserLoanRepayments')->name('backend.users.get_user_loan_repayments');
        });
});
