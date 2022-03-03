<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgentStakeholderEvalu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_stakeholder_evalu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('agent_id');
            $table->double('mark')->default(0);
            $table->boolean('status')->default(0)->comment('0 = Fail and 1 = Pass');
            $table->foreign('user_id')->references('id')->on('sujog_users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('sujog_users')->onDelete('cascade');
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
        Schema::dropIfExists('');
    }
}
