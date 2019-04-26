<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagazinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->unsigned()->index(); 
            $table->string('name')->nullable();
            $table->integer('lenght')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('year')->nullable();//>0<=currentYear()
            $table->integer('number')->nullable();//>0            
            $table->integer('number_per_year')->nullable();//>0
            $table->string('dimensions')->nullable();
            $table->float('price')->default(0);//newPrice
            $table->integer('sub_price')->nullable();//no require >0                
            $table->integer('status_sub_price')->default(0);
            $table->float('old_price')->default(0);//extra//>0
            $table->string('img')->default('no_image.jpg');
            $table->integer('code')->default('0');//newCode
            $table->integer('discont_global')->nullable(); //>0           
            $table->integer('status_discont_global')->default(0);//on/of user privat global discont        
            $table->integer('discont_id')->nullable();
            $table->integer('status_discont_id')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('status_draft')->default(0);
            $table->timestamps();
        });
         Schema::table('magazines', function (Blueprint $table) {
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magazines');
    }
}
