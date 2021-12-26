<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class RawMaterialStockCheck implements Rule
{
    protected $sub_raw_material_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($field, $request)
    {
        $this->sub_raw_material_id = $request->get('sub_raw_material_ids');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value > $this->sub_raw_material_id) === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The raw material stock not available.';
    }
}
