<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Validators\LoanValidator;


class LoanController
{
    public function apply(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $validator = new LoanValidator();

        try {
            $validator->validate($data);

            // logic to save load file
            $logFilePath = __DIR__ . '/../../logs/loan_applications.txt';

            if (!is_dir(dirname($logFilePath))) {
                mkdir(dirname($logFilePath), 0777, true);
            }
    
            $logData = json_encode($data) . PHP_EOL;
    
            file_put_contents($logFilePath, $logData, FILE_APPEND);

            $response->getBody()->write(json_encode([
                'message' => 'Application submitted successfully.'
            ]));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(200);
                            
        } catch (\Respect\Validation\Exceptions\NestedValidationException $exception) {
            $response->getBody()->write(json_encode([
                'errors' => $exception->getMessages()
            ]));
            return $response->withHeader('Content-Type', 'application/json')
                            ->withStatus(400);
        }
    }
}