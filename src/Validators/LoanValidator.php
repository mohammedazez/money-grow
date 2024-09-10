<?php

namespace App\Validators;

use Respect\Validation\Validator as v;


class LoanValidator
{
    public function validate($data)
    {
        $validator = v::key('name', v::stringType()->notEmpty()->regex('/\w+\s+\w+/'))
            ->key('ktp', v::callback(function ($ktp) use ($data) {
                $sex = $data['sex'] ?? '';
                $dob = $data['dob'] ?? '';

                if ($sex == 'male') {
                    return preg_match('/^\d{6}\d{2}\d{2}\d{2}\d{4}$/', $ktp) === 1;
                } elseif ($sex == 'female') {
                    return preg_match('/^\d{6}\d{2}\d{2}\d{2}\d{4}$/', $ktp) === 1;
                }
                return false;
            }))
            ->key('loan_amount', v::intVal()->between(1000, 10000))
            ->key('loan_purpose', v::in(['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment']))
            ->key('dob', v::date('Y-m-d'))
            ->key('sex', v::in(['male', 'female']));
    }
}