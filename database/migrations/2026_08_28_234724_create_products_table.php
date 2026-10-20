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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // foreignId significa foreing key = chave estrangeira
            // constrained = relacionamento
            $table->foreignId('category_id')->constrained('categories');
            $table->string('name');
            // string = textos curtos
            // text = textos longos
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->string('color');
            $table->string('size');
            $table->string('material');
            // nullable significa que um produto pode vir inicialmente sem uma img
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
