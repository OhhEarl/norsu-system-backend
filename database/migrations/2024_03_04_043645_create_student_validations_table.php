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
        Schema::create('student_validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_name')->nullable();
            $table->unsignedBigInteger('area_of_expertise')->nullable();
            $table->string('norsu_id_number')->nullable();
            $table->string('course')->nullable();
            $table->unsignedBigInteger('year_level')->nullable();
            $table->string('front_id')->nullable();
            $table->string('back_id')->nullable();
            $table->longText('about_me')->nullable();
            $table->string('user_avatar')->default('storage/DefaultAvatar.png');
            $table->boolean('is_student')->default(0);
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->foreign('area_of_expertise')->references('id')->on('expertises')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('year_level')->references('id')->on('year_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_validations');
    }
};
