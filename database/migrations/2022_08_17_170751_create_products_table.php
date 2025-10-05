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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->char('code', 50)->unique();
            $table->string('name');
            $table->string('name_kana')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable()->default(0);
            $table->unsignedBigInteger('price')->nullable()->default(0);
            $table->bigInteger('quantity')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();
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
};
