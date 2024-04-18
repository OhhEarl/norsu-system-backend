<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('create_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("student_user_id")->nullable();
            $table->string("job_title")->nullable();
            $table->unsignedBigInteger("job_category_id")->nullable();
            $table->longText("job_description")->nullable();
            $table->date("job_start_date")->nullable();
            $table->date("job_end_date")->nullable();
            $table->double("job_budget_from")->nullable();
            $table->double("job_budget_to")->nullable();
            $table->boolean("job_finished")->default(0)->nullable();
            $table->timestamps();



            $table->foreign('student_user_id')->references('id')->on('student_validations')
                ->onUpdate('cascade')
                ->onDelete('cascade');



            $table->foreign('job_category_id')->references('id')->on('job_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_jobs');
    }
};
