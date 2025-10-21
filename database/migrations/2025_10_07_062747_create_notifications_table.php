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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // direct user
            $table->unsignedBigInteger('sender_id')->nullable(); // who triggered it
            $table->string('type'); // task_assigned, comment_added, status_changed, project_created, data_updated
            $table->string('title'); // short title
            $table->text('message'); // full message
            $table->unsignedBigInteger('related_id')->nullable(); // task_id or project_id
            $table->string('related_type')->nullable(); // 'task' or 'project'
            $table->boolean('is_read')->default(false);
            $table->json('roles')->nullable(); // roles to notify if user_id is null
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
