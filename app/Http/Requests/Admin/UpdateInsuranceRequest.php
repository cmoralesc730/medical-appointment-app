<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;

class UpdateInsuranceRequest extends InsuranceRequest
{
    protected function policyNumberRule(): \Illuminate\Validation\Rules\Unique
    {
        $insurance = $this->route('insurance');

        return Rule::unique('insurances', 'policy_number')->ignore($insurance->id);
    }
}
