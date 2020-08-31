<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project_id')->nullable();
            $table->string('name')->nullable();
            $table->string('client_name')->nullable();
            $table->date('start_date')->nullable();
            $table->decimal('budget', 8, 2)->nullable();
            $table->decimal('tracked', 8, 2)->nullable();
            $table->decimal('billable', 8, 2)->nullable();
            $table->integer('owner_id')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('health')->nullable();
            $table->text('status')->nullable();
            $table->timestamp('project_created_at')->nullable();
            $table->timestamp('project_updated_at')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
