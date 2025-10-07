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
            $table->unsignedBigInteger('project_id')->nullable();

            $table->dateTime('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('assigned_to')->references('user_id')->on('tbl_user')->onDelete('set null');
            $table->foreign('created_by')->references('user_id')->on('tbl_user')->onDelete('cascade');
            $table->foreign('status_id')->references('status_id')->on('statuses')->onDelete('cascade');
            $table->foreign('priority_id')->references('priority_id')->on('priorities')->onDelete('cascade');
            $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('set null');
            $table->foreign('project_id')->references('project_id')->on('projects')->onDelete('set null');
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
