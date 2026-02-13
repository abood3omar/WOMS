<?php

// database/migrations/xxxx_xx_xx_create_work_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {

            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->foreignId('assigned_to')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->date('due_date')->nullable();
            $table->timestamps(); 
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
};