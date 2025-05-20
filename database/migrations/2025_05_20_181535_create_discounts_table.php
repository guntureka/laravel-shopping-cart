<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string("code")->unique();
            $table->enum("type",['percentage', 'fixed', 'product_specific', 'time_based', "price_threshold"]);
            $table->float("value")->min(0)->max(100);
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->float('min_price')->nullable();
            $table->string('day')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
