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
        Schema::create('proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_proofs')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->enum('status', ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'])->default('Not Submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proofs');
    }
};
