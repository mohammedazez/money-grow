<?php

namespace App\Validators;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;



class LoanValidator
{
    public function validate($data)
    {
        $validator = v::key('name', v::stringType()->notEmpty()->regex('/\w+\s+\w+/'))
        ->key('ktp', v::callback(function ($ktp) use ($data) {
            $dob = $data['dob'] ?? '';
            $sex = $data['sex'] ?? '';

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
                return false;
            }

            [$year, $month, $day] = explode('-', $dob);

            $year = substr($year, -2);

            // For women, add 40 to the day part
            if ($sex == 'female') {
                $day = (int)$day + 40;
            }
            $expectedDobPart = sprintf('%02d%02d%s', $day, $month, $year);
            return preg_match('/^\d{6}' . $expectedDobPart . '\d{4}$/', $ktp) === 1;
        }))
            ->key('loan_amount', v::intVal()->between(1000, 10000))
            ->key('loan_purpose', v::in(['vacation', 'renovation', 'electronics', 'wedding', 'rent', 'car', 'investment']))
            ->key('dob', v::date('Y-m-d'))
            ->key('sex', v::in(['male', 'female']))
            ->key('loan_period_months', v::intVal()->between(1, 60));

            try {
                $validator->assert($data);
            } catch (NestedValidationException $exception) {
                $exception->getMessages([
                    "name" => "Name must be present and include at least two words.",
                    "ktp" => "KTP must be a 16-digit number and should follow the correct format based on the date of birth and gender.",
                    "loan_amount" => "Loan amount must be between 1000 and 10000.",
                    "loan_purpose" => "Loan purpose must be one of: vacation, renovation, electronics, wedding, rent, car, investment.",
                    "dob" => "Date of birth must be in YYYY-MM-DD format.",
                    "sex" => "Sex must be either 'male' or 'female'."
                ]);
                
                throw $exception;
            }
    }
}