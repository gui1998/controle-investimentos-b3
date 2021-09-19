<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->date('payment_date')->default(\Carbon\Carbon::now());
            $table->integer('stock_amount');
            $table->decimal('discount')->nullable();
            $table->decimal('total');

            $table->foreignId('stock_id')->constrained('stocks');
            $table->foreignId('income_type_id')->constrained('income_types');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
