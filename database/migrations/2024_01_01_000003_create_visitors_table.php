<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('visit_date');
            $table->string('invited_by')->nullable();
            $table->string('service_attended')->nullable();
            $table->string('follow_up_status')->default('pending');
            $table->text('follow_up_notes')->nullable();
            $table->boolean('converted_to_member')->default(false);
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->string('prayer_request')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitors');
    }
};
