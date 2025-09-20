<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('task_id');
            $table->string('title', 255);
            $table->text('description')->nullable();

            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('priority_id');
            $table->unsignedBigInteger('tag_id')->nullable();

            $table->dateTime('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
