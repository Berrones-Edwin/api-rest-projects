<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTanslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_translations', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('project_id')->unsigned();

            $table->string('locale')->index();

            // Campos que se van a traducir
            $table->string('title');
            $table->string('description');

            $table->unique(['project_id','locale']);



            $table->foreign('project_id')
                   ->references('id')
                   ->on('projects')
                   ->onDelete('cascade');

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
        Schema::dropIfExists('project_translations');
    }
}
