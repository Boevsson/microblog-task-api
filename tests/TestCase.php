<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class TestCase extends BaseTestCase
{
    protected App $app;
    protected bool $refreshDatabase = false;

    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    protected function setUp(): void
    {
        $this->createApp();

        parent::setUp();

        if ($this->refreshDatabase) {

            $this->importDataSet();
        }

        $this->refreshDatabase = false;
    }

    public function createApp()
    {
        // Use the application settings
        $settings = require __DIR__ . '/../config/settings.php';

        // Instantiate the application
        $this->app = new App($settings);

        // Set up dependencies
        $dependencies = require __DIR__ . '/../config/dependencies.php';
        $dependencies($this->app);

        // Register routes
        $routes = require __DIR__ . '/../src/routes.php';
        $routes($this->app);

        return $this->app;
    }

    public function makeRequest($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri,
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Process the application
        $response = $this->app->process($request, $response);

        // Return the response
        return $response;
    }

    protected function getTestSeedDataFile(): string
    {
        $targetSQL = __DIR__ . '/TestSeedData.sql';

        if (file_exists($targetSQL) === false) {

            throw new \Exception("Test dataset $targetSQL does not exist or is not accessible.");
        }

        return $targetSQL;
    }

    public function importDataSet()
    {
        $sqlFile = $this->getTestSeedDataFile();
        $sql = file_get_contents($sqlFile);

        $this->app->getContainer()->get('db')->getConnection()->getPdo()->exec($sql);
    }
}