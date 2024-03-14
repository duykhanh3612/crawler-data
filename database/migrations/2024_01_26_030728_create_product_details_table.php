<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('category');
            $table->decimal('price', 10, 2); // Sử dụng kiểu decimal cho giá, có 10 chữ số tổng và 2 chữ số sau dấu thập phân
            $table->float('rate'); // Sử dụng kiểu float cho rate
            $table->integer('quantity_sold'); // Sử dụng kiểu integer cho quantity_sold
            $table->string('categories');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_details');
    }
};
