<?php

use \Mockery as m;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HandlerTest extends TestCase
{
    public function testRespondsWithHTMLWhenJSONIsNotAccepted()
    {
        // TODO: Assume they want JSON and always return JSON #yolo
        // Make the mock a partial, we only want to mock the `isDebugMode` method
        $subject = m::mock(Handler::class)->makePartial();
        $subject->shouldNotReceive('isDebugMode');

        // Mock the interaction with the Request
        $request = m::mock(Request::class);
        $request->shouldReceive('wantsJson')->andReturn(false);

        // Mock the interaction with the exception
        $exception = m::mock(\Exception::class, ['Error!']);
        $exception->shouldNotReceive('getStatusCode');
        $exception->shouldNotReceive('getTrace');
        $exception->shouldNotReceive('getMessage');

        // Call the method under test, this is not a mocked method.
        $result = $subject->render($request, $exception);

        // Assert that `render` does not return a JsonResponse
        $this->assertNotInstanceOf(JsonResponse::class, $result);
    }

    public function testRespondsWithJSONWhenRequested()
    {
        $subject = m::mock(Handler::class)->makePartial();
        $subject
            ->shouldReceive('isDebugMode')
            ->andReturn(false);

        $request = m::mock(Request::class);
        $request
            ->shouldReceive('wantsJson')
            ->andReturn(true);

        // TODO: Change wording
        $exception = m::mock(\Exception::class, ['Doh!']);
        $exception
            ->shouldReceive('getMessage')
            ->andReturn('Doh!');

        /** @var JsonResponse $result */
        $result = $subject->render($request, $exception);
        $data = $result->getData();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertObjectHasAttribute('error', $data);
        $this->assertAttributeEquals('Doh!', 'message', $data->error);
        $this->assertAttributeEquals(400, 'status', $data->error);
    }

    public function testRespondsWithJSONforHTTPExceptions()
    {
        $subject = m::mock(Handler::class)->makePartial();
        $subject
            ->shouldReceive('isDebugMode')
            ->andReturn(false);

        $request = m::mock(Request::class);
        $request->shouldReceive('wantsJson')->andReturn(true);

        $examples = [
            [
                'mock' => NotFoundHttpException::class,
                'status' => 404,
                'message' => 'Not Found'
            ],
            [
                'mock' => AccessDeniedHttpException::class,
                'status' => 403,
                'message' => 'Forbidden'
            ],
            [
                'mock' => ModelNotFoundException::class,
                'status' => 404,
                'message' => 'Not Found'
            ]
        ];

        foreach ($examples as $e) {
            $exception = m::mock($e['mock']);
            $exception->shouldReceive('getMessage')->andReturn(null);
            $exception->shouldReceive('getStatusCode')->andReturn($e['status']);
            $result = $subject->render($request, $exception);
            $data = $result->getData();
            $this->assertEquals($e['status'], $result->getStatusCode());
            $this->assertEquals($e['message'], $data->error->message);
            $this->assertEquals($e['status'], $data->error->status);
        }
    }
}
