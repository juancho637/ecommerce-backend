<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->string('type');
            $table->string('name');
            $table->string('slug');
            $table->unsignedDecimal('price', 12, 2);
            $table->unsignedDecimal('tax', 4, 2);
            $table->string('sku')->unique();
            $table->boolean('is_variable')->default(false);
            $table->string('short_description', 600)->nullable();
            $table->text('description')->nullable();
            $table->text('options')->nullable();
            $table->timestamps();

            if (env('APP_ENV') !== 'testing') {
                $table->fullText('short_description');
                $table->fullText('description');
                $table->fullText('options');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
