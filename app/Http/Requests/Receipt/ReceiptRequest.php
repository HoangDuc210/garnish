<?php

namespace App\Http\Requests\Receipt;

use App\Enums\Receipt\Type;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ReceiptRequest extends FormRequest
{

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'type_code' => Type::Common,
        ]);
        if ($this->transaction_start_date) {
            $date = DateTime::createFromFormat("Y/m/d", $this->transaction_start_date);
            $this->merge([
                'transaction_start_date' => $date ? $this->transaction_start_date : Carbon::now(),
            ]);
        }else{
            $this->merge([
                'transaction_start_date' => null,
            ]);
        }

    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
        ];
    }
}
