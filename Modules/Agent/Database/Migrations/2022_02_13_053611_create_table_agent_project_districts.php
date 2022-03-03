<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgentProjectDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_project_districts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_project_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('division_id');

            $table->foreign('agent_project_id')->references('id')
            ->on('agent_projects')
            ->onDelete('cascade');

            $table->foreign('district_id')->references('id')
            ->on('districts')
            ->onDelete('cascade');

            $table->foreign('division_id')->references('id')
            ->on('divisions')
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
        Schema::dropIfExists('agent_project_districts');
    }
}
