<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->unsigned()->index();
            $table->string('name')->default(0);
            $table->string('author_name')->nullable();
            $table->integer('lenght')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('year')->nullable();
            //or enum('format', ['Hardcover','Kindle'])?
            $table->string('format')->nullable();//laravel checkbox
            //$table->string('is_hard_cover')->('Hardcover');//laravel checkbox
            // $table->string('is_kindle')->('Kindle');//laravel checkbox
            $table->integer('is_hard_cover')->default(0);
            $table->string('genre')->nullable();
            $table->string('dimensions')->nullable();
            $table->float('price')->default(0);
            $table->float('old_price')->default(0);//extra
            $table->string('img')->default('no_image.jpg');
            $table->integer('code')->default('0');//newCode??
            $table->integer('discont_global')->nullable();            
            $table->integer('status_discont_global')->default(0);//on/of user privat global discont                 
            $table->integer('discont_id')->nullable();       
            $table->integer('status_discont_id')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('status_draft')->default(0);
            $table->timestamps();
        });
        Schema::table('books', function (Blueprint $table) {
            
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
        Schema::dropIfExists('books');
        //dropPrimary('users_id_primary') dropUnique('users_emue') dropIndex('geo_state_index')
        //dropTimestamps() 
        //dropSoftDeletes()
    }
}
