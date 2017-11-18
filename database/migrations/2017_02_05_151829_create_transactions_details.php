<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('transactions_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transactions_id',30)->nullable();
            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');            
            $table->bigInteger('products_id')->unsigned()->nullable();
            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');            
            $table->decimal('real_price',10,2)->nullable()->default('0');
            $table->decimal('bargain_price',10,2)->nullable()->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions_details');
    }
}
