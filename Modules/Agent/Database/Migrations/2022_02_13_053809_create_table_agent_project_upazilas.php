<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgentProjectUpazilas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_project_upazilas', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('agent_project_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('upazila_id');

            $table->foreign('agent_project_id')->references('id')
            ->on('agent_projects')
            ->onDelete('cascade');

            $table->foreign('district_id')->references('id')
            ->on('districts')
            ->onDelete('cascade');

            $table->foreign('upazila_id')->references('id')
            ->on('upazilas')
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
        Schema::dropIfExists('agent_project_upazilas');
    }
}
