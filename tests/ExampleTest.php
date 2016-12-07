<?php

//use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     * Since we're not doing anything with "/" currently I left
     * this in. -AKF
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(), $this->response->getContent()
        );
    }
}
