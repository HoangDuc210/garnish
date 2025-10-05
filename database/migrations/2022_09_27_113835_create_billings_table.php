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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('billing_agent_id');
            $table->date('calculate_date');
            $table->bigInteger('last_billed_amount')->default(0);
            $table->bigInteger('deposit_amount')->default(0);
            $table->bigInteger('receipt_amount')->default(0);
            $table->bigInteger('collection_amount')->default(0);
            $table->bigInteger('tax_amount')->default(0);
            $table->bigInteger('carried_forward_amount')->default(0);

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
        Schema::dropIfExists('billings');
    }
};
