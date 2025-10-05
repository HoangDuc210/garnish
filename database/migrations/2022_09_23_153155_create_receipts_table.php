<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Receipt\Type;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('code');
            $table->enum('type_code', Type::getValues())
                ->default(Type::Common);
            $table->unsignedBigInteger('agent_id');
            $table->date('transaction_date');
            $table->tinyInteger('print_status')->nullable()->default(0);
            $table->string('memo')->nullable();
            $table->tinyInteger('tax')->nullable()->default(10);

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
        Schema::dropIfExists('receipts');
    }
};
