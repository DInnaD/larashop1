<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->unsignedBigInteger('purchaseable_id');//??
            $table->string('purchseable_type');//??
            $table->unsignedBigInteger('user_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('order_id')->nullable()->unsigned()->index();
            $table->integer('qty')->nullable();
            //$table->integer('code')->nullable();
            $table->integer('status_bought')->default(0);//buy button click with new order_id
            $table->integer('status_paid')->default(0);//if admin saw money an acount          
            $table->integer('created_by')->nullable();           
            $table->timestamps();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
