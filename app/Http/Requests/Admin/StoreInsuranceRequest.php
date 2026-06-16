<?php

namespace App\Http\Requests\Admin;

class StoreInsuranceRequest extends InsuranceRequest
{
    protected function policyNumberRule(): string
    {
        return 'unique:insurances,policy_number';
    }
}
