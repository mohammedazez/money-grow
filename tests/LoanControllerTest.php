<?php

use PHPUnit\Framework\TestCase;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;
use App\Controllers\LoanController;

class LoanControllerTest extends TestCase
{
    private $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = AppFactory::create();

        $this->app->addRoutingMiddleware();

        $this->app->post('/loan', [LoanController::class, 'apply']);
    }

    public function testValidLoanApplication()
    {
        $app = AppFactory::create();
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/loan');
        $request = $request->withParsedBody([
            'name' => 'John Doe',
            'ktp' => '1234560101901234',
            'loan_amount' => 5000,
            'loan_period_months' => 2,
            'loan_purpose' => 'vacation',
            'dob' => '1990-01-01',
            'sex' => 'male',
        ]);

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testInvalidKtp()
    {
        $app = AppFactory::create();
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/loan');
        $request = $request->withParsedBody([
            'name' => 'John Doe',
            'ktp' => 'invalidktp',
            'loan_amount' => 5000,
            'loan_period_months' => 2,  
            'loan_purpose' => 'vacation',
            'dob' => '1990-01-01',
            'sex' => 'male',
        ]);

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testInvalidLoanAmount()
    {
        $app = AppFactory::create();
        $request = (new ServerRequestFactory())->createServerRequest('POST', '/loan');
        $request = $request->withParsedBody([
            'name' => 'John Doe',
            'ktp' => '1234560101991234',
            'loan_amount' => 100,
            'loan_period_months' => 2,  
            'loan_purpose' => 'vacation',
            'dob' => '1990-01-01',
            'sex' => 'male',
        ]);

        $response = $this->app->handle($request);

        $this->assertEquals(400, $response->getStatusCode());
    }
}
