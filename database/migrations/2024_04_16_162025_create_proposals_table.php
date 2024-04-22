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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('freelancer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('job_title')->nullable();
            $table->text('expertise_explain')->nullable();
            $table->date('due_date')->nullable();
            $table->string('job_amount_bid')->nullable();
            $table->boolean('status')->default(0);

            $table->timestamps();


            $table->foreign('project_id')->references('id')->on('create_jobs')->onDelete('cascade');
            $table->foreign('freelancer_id')->references('id')->on('student_validations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('student_validations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
