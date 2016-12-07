<?php

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TestCase extends Laravel\Lumen\Testing\TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * See if the response has a header.
     *
     * @param $header
     * @return $this
     *
     * Thanks to Paul Redmond for this!
     */
    public function seeHasHeader($header)
    {
        $this->assertTrue(
            $this->response->headers->has($header),
            "Response should have the header '{$header}' but does not."
        );
        return $this;
    }

    /**
     * Asserts that the response header matches a given regular expression
     *
     * @param $header
     * @param $regexp
     * @return $this
     *
     * Another hat tip to Paul Redmond for this too.
     */
    public function seeHeaderWithRegExp($header, $regexp)
    {
        $this
            ->seeHasHeader($header)
            ->assertRegExp(
                $regexp,
                $this->response->headers->get($header)
            );
        return $this;
    }
}

