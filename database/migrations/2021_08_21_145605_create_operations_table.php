<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->date('operation_date')->default(\Carbon\Carbon::now());
            $table->char('buy_r_sell', 1);
            $table->integer('stock_amount');
            $table->decimal('price', 10,2);
            $table->decimal('cost', 10,2)->nullable();
            $table->decimal('irrf', 10,2)->nullable();
            $table->foreignId('stock_id')->constrained();
            $table->foreignId('operation_type_id')->constrained();
            $table->foreignId('broker_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('operations');
    }
}
