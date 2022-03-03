<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAgentEvaluQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_evalu_questions', function (Blueprint $table) {
            $table->id();
            $table->longText('question');
            $table->string('ans_a')->nullable();
            $table->string('ans_b')->nullable();
            $table->string('ans_c')->nullable();
            $table->string('ans_d')->nullable();
            $table->string('ans_e')->nullable();
            $table->enum('question_type',['1','2','3'])->nullable()->comment("1 = single select, 2 = multiselect,3= input");
            $table->string('correct_answer')->nullable();
            $table->double('mark')->default(0);
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
