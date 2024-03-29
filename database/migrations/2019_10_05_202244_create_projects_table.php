<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('slug');
            $table->string('thumbnail')->nullable();
            $table->string('image')->nullable();
            $table->integer('user_id')->unsigned();

            $table->foreign('user_id')
                   ->references('id')
                   ->on('users')
                   ->onDelete('cascade');

                   /**
                    * $table->foreign('rol_id','fk_permisorol_rol')->references('id')->on('rol')->onDelete('restrict')->onUpdate('restrict');
                    */

            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
