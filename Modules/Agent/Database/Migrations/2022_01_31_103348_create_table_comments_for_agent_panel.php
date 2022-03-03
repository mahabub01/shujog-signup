<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCommentsForAgentPanel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_stakeholder_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('agent_id');
            $table->longText('comment')->nullable();
            $table->enum('status',[1,2,3,4])->comment('1= pending, 2= hold on, 3= complete, 4= reject');
            $table->enum('status_type',[0,1,2,3,4])->default('0')->comment('0=none, 1= pending, 2= hold on, 3= complete, 4= reject');
            $table->unsignedBigInteger('role_id');
            $table->foreign('user_id')->references('id')->on('sujog_users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('sujog_users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
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
