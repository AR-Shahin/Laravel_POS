<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice__details', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable()
            ;
            $table->foreignId('invoice_id');
            $table->foreignId('category_id');
            $table->foreignId('product_id');
            $table->double('selling_qty');
            $table->double('unit_price');
            $table->double('selling_price');
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('invoice__details');
    }
}
