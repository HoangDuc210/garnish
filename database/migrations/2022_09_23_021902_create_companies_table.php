<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\General\Functions;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('name_kana')->nullable();
            $table->char('post_code', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('address_more')->nullable();
            $table->char('tel', 50)->nullable();
            $table->char('fax', 50)->nullable();
            $table->string('bank_account')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('companies');
    }
};
