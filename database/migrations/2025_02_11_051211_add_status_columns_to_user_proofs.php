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
        Schema::table('user_proofs', function (Blueprint $table) {
            $table->enum('id_proof_status', ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'])->default('Not Submitted')->after('id_proof');
            $table->enum('address_proof_status', ['Not Submitted', 'Waiting for Approval', 'Approved', 'Rejected'])->default('Not Submitted')->after('address_proof');
        });
    }
    
    public function down()
    {
        Schema::table('user_proofs', function (Blueprint $table) {
            $table->dropColumn('id_proof_status');
            $table->dropColumn('address_proof_status');
        });
    }
    
};
