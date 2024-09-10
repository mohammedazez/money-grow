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
        } catch (\Respect\Validation\Exceptions\NestedValidationException $exception) {
            $errors = $exception->getMessages();
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json')
                ->getBody()->write(json_encode(['errors' => $errors]));
        }

    }
}