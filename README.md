# Money Grow Loan Application API

## Overview

The **Money Grow Loan Application API** is designed to accept loan applications with basic validation. The API accepts user data such as name, KTP number, loan amount, loan purpose, date of birth, and gender. All fields are validated before processing the loan application.

## Table of Contents

- [Endpoints](#endpoints)
- [Request Fields](#request-fields)
- [Response](#response)
- [Setup and Installation](#setup-and-installation)
- [Logging](#logging)
- [Dependencies](#dependencies)

## Endpoints

### POST `/loan`

This endpoint allows users to submit a loan application.

#### Request Fields

| Field         | Type    | Required | Description                                                                                          |
|---------------|---------|----------|------------------------------------------------------------------------------------------------------|
| `name`        | string  | Yes      | Full name of the applicant (must include at least two words).                                         |
| `ktp`         | string  | Yes      | Indonesian 16-digit KTP number and valid format for male or female.                                                                      |
| `loan_amount` | integer | Yes      | Loan amount between 1000 and 10000.                                                                  |
| `loan_period_months` | integer | Yes      | Loan period months amount between 1 and 60.                                                                  |
| `loan_purpose`| string  | Yes      | Purpose of the loan. Must be one of: `vacation`, `renovation`, `electronics`, `wedding`, `rent`, `car`, `investment`. |
| `dob`         | string  | Yes      | Date of birth in `YYYY-MM-DD` format.                                                                |
| `sex`         | string  | Yes      | Applicant's gender, either `male` or `female`.                                                       |

#### Example Request (cURL)

```
curl --location 'http://localhost:8000/loan' \
--header 'Content-Type: application/json' \
--data '{
    "name": "John Doe",
    "ktp": "1234560101991234",
    "loan_amount": 5000,
    "loan_period_months": 2,
    "loan_purpose": "vacation",
    "dob": "1990-01-01",
    "sex": "male"
}`
```

## Response
Success Response (200 OK)

If the application is valid, the server will respond with:
```
{
    "message": "Application submitted successfully."
}
```

Error Response (400 Bad Request)

If validation fails, the server will respond with detailed error messages for each invalid field. 

Example:
```
{
    "errors": {
        "name": "Name must be present and include at least two words.",
        "ktp": "KTP must be a 16-digit number and should follow the correct format based on the date of birth and gender.",
        "loan_amount": "Loan amount must be between 1000 and 10000.",
        "loan_period_months": "loan_period_months must be between 1 and 60",
        "loan_purpose": "Loan purpose must be one of: vacation, renovation, electronics, wedding, rent, car, investment.",
        "dob": "Date of birth must be in YYYY-MM-DD format.",
        "sex": "Sex must be either 'male' or 'female'."
    }
}
```

## Setup and Installation
Prerequisites
- PHP 7.4 or higher
- Composer
- A local server (e.g., XAMPP, WAMP, MAMP)


1. Installation
Clone the repository:
```
git clone https://github.com/your-username/money-grow.git
cd money-grow
```

2. Install the dependencies:
```
composer install
```

3. Create a logs directory for storing loan application logs:
```
mkdir logs
```

4. Start the PHP built-in server or run the app in your local server (e.g., XAMPP):
```
php -S localhost:8000 -t public
```

5. Start unit tests:
```
vendor/bin/phpunit
```

```
Directory Structure
money-grow/
├── public/
│   └── index.php          # Main entry point
├── src/
│   ├── Controllers/
│   │   └── LoanController.php  # Handles the loan application logic
│   ├── Validators/
│   │   └── LoanValidator.php   # Contains validation logic
├── logs/                  # Directory for storing loan application logs
├── tests/
│   └── LoanControllerTest.php   # Contains unit tests
├── vendor/                # Composer dependencies
├── composer.json          # Composer configuration file
└── README.md              # This documentation
```

## Logging
The loan applications are logged in the /logs/loan_applications.txt file. Ensure that this directory exists and is writable by your web server. Each application submission appends a log entry containing the submitted data.

## Dependencies
Slim Framework (v4): A lightweight PHP framework for building APIs and web applications.
Respect/Validation: A powerful validation library used to ensure the correctness of user input.
PSR-7: HTTP message interfaces for request and response objects.
phpunit/phpunit:  A unit testing framework for PHP.
