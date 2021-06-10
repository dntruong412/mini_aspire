<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_loans', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->unsignedInteger('user_id');

            $table->bigInteger('amount');
            $table->bigInteger('debt');
            $table->unsignedInteger('duration')->comment('months');
            $table->unsignedInteger('repayment_frequency');
            $table->unsignedInteger('interest_rate')->comment('%');
            $table->unsignedInteger('arrangement_fee');
            $table->unsignedInteger('payment_status')->default(1)->comment('1:Paying 2:Paid');

            $table->tinyInteger('status')->nullable()->default(1)->comment('0:Inactive 1:Active');
            $table->nullableTimestamps(0);
            $table->softDeletes('deleted_at', 0);

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_loans');
    }
}
