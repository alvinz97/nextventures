<?php

use App\Models\User;
use Domain\Order\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class, 'order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('status', [
                'success',
                'failed'
            ]);
            $table->decimal('total', 12, 2);
            $table->string('message')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('order_notifications');
    }
};
