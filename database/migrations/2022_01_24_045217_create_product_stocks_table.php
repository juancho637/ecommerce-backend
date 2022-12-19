<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('stock')->nullable();
            $table->unsignedDecimal('price', 12, 2);
            $table->unsignedDecimal('width', 12, 2)->nullable();
            $table->unsignedDecimal('height', 12, 2)->nullable();
            $table->unsignedDecimal('length', 12, 2)->nullable();
            $table->unsignedDecimal('weight', 12, 2)->nullable();
            $table->string('sku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}
