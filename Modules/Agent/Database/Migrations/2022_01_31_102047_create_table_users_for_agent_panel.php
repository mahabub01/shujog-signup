<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsersForAgentPanel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_stakeholders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('consultant_status',[1,2,3,4])->comment(' 1= pending, 2= hold on, 3= complete, 4= reject');
            $table->enum('trainer_status',[0,1,2,3,4])->default('0')->comment('0=none, 1= pending, 2= hold on, 3= complete, 4= reject');
            $table->enum('deployer_status',[0,1,2,3,4])->default('0')->comment('0=none, 1= pending, 2= hold on, 3= complete, 4= reject');
            $table->enum('network_status',[0,1,2,3])->default('0')->comment('0=none, 1= active, 2= in active, 3= Dropout');
            $table->boolean('project_manager')->default(1)->comment('0=deactive, 1= active');
            $table->foreign('user_id')->references('id')->on('sujog_users')->onDelete('cascade');
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
        Schema::dropIfExists('agent_users');
    }
}
