<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Agent\FractionRounding;
use App\Enums\Agent\TaxFractionRounding;
use App\Enums\Agent\TaxTaxationMethod;
use App\Enums\Agent\TaxType;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('name_kana')->nullable();
            $table->char('code', 50);
            $table->char('post_code', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('address_more')->nullable();
            $table->char('tel', 50)->nullable();
            $table->char('fax', 50)->nullable();
            $table->integer('closing_date')->default(31);
            $table->enum('fraction_rounding_code', FractionRounding::getValues())
                ->default(FractionRounding::FourDownFiveUp);
            $table->enum('tax_type_code', TaxType::getValues())
                ->default(TaxType::TaxIncluded);
            $table->enum('tax_fraction_rounding_code', TaxFractionRounding::getValues())
                ->default(TaxFractionRounding::FourDownFiveUp);
            $table->enum('tax_taxation_method_code', TaxTaxationMethod::getValues())
                ->default(TaxTaxationMethod::Billing);
            $table->unsignedBigInteger('billing_agent_id');
            $table->string('billing_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_address_more')->nullable();
            $table->char('billing_tel', 50)->nullable();
            $table->char('billing_fax', 50)->nullable();
            $table->char('billing_source_company_id', 50);

            $table->tinyInteger('tax_rate')->default(0)->nullable();
            $table->decimal('collection_rate', 4)->default(2)->nullable();
            $table->integer('print_type')->default(4)->nullable();

            $table->string('memo')->nullable();

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
        Schema::dropIfExists('agents');
    }
};
