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
    Schema::create('user_proofs', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('id_proof')->nullable(); // ID Proof file or number
        $table->enum('id_proof_status', ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'])->default('Not Submitted');
        $table->string('address_proof')->nullable(); // Address Proof file or description
        $table->enum('address_proof_status', ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'])->default('Not Submitted');
        $table->enum('status', ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'])->default('Not Submitted');
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_proofs');
    }
};
