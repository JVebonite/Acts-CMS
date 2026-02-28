<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('service_type')->nullable();
            $table->date('service_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('check_in_method')->default('manual');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['member_id', 'service_id', 'service_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
