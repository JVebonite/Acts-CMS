<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('membership_status')->default('active');
            $table->date('membership_date')->nullable();
            $table->date('baptism_date')->nullable();
            $table->date('wedding_anniversary')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('family_id')->nullable()->constrained('families')->nullOnDelete();
            $table->string('family_role')->nullable();
            $table->string('membership_class')->nullable();
            $table->string('qr_code')->nullable()->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('members');
    }
};
