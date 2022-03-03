<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgentStakeholderEvaluDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_stkholder_eval_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_stkholder_evalu_id');
            $table->unsignedBigInteger('agent_ev_qus_id');
            $table->string('answer');
            $table->boolean('status')->comment("0 = wrong, 1= right");
            $table->double('mark')->default(0);

            $table->foreign('agent_stkholder_evalu_id')->references('id')
            ->on('agent_stakeholder_evalu')
            ->onDelete('cascade');

            $table->foreign('agent_ev_qus_id')->references('id')
            ->on('agent_evalu_questions')
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
        Schema::dropIfExists('');
    }
}
