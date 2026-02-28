<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cluster_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cluster_id')->constrained('clusters')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('role')->default('member');
            $table->date('joined_date')->nullable();
            $table->timestamps();

            $table->unique(['cluster_id', 'member_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cluster_members');
    }
};
