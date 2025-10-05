<?php

use App\Enums\Deposit\Type;
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
        Schema::create('deposits_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('deposit_id')->default(0);
            $table->unsignedBigInteger('billing_agent_id')->default(0);
            $table->enum('type_code', Type::getValues())
                ->default(Type::Cash);
            $table->date('payment_date');
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('memo');

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
        Schema::dropIfExists('deposits_detail');
    }
};
