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
        Schema::create('job_proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('create_job_id');

            $table->timestamps();

            $table->foreign('create_job_id')->references('id')->on('create_jobs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_proposals');
    }
};
