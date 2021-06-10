<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoanRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_loan_repayments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_loan_id', 36);
            $table->unsignedInteger('amount');
            $table->tinyInteger('status')->nullable()->default(1)->comment('0:Inactive 1:Active');
            $table->nullableTimestamps(0);
            $table->softDeletes('deleted_at', 0);

            $table->foreign('user_loan_id')->references('id')->on('user_loans')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_loan_repayments');
    }
}
