<?php
// database/migrations/xxxx_xx_xx_create_work_order_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_order_logs', function (Blueprint $table) {

            $table->id();
            $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
            $table->string('action');
            $table->foreignId('performed_by')->constrained('users', 'id');
            $table->timestamps(); 
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_order_logs');
    }
};