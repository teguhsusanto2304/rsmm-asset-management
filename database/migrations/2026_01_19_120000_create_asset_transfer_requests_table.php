<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_transfer_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->uuid('requested_by');
            $table->uuid('requested_from'); // Current asset owner
            $table->uuid('requested_to'); // Who wants to borrow/receive
            $table->enum('type', ['borrow', 'move'])->default('borrow'); // borrow or move
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'returned'])->default('pending');
            $table->date('request_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('asset_id')->references('id')->on('asset')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_from')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('requested_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_transfer_requests');
    }
};
