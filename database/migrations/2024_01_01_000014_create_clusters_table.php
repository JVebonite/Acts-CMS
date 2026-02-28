<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('leader_id')->nullable()->constrained('members')->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('meeting_day')->nullable();
            $table->time('meeting_time')->nullable();
            $table->string('location')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clusters');
    }
};
