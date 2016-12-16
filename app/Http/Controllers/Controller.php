<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Responses\FractalResponse;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    private $fractal;

    public function __construct(FractalResponse $fractal) {
        $this->fractal = $fractal;
        //$this->fractal->parseIncludes();
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $resourceKey
     * @return array
     */
    public function item($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->fractal->item($data, $transformer, $resourceKey);
    }

    /**
     * @param $data
     * @param TransformerAbstract $transformer
     * @param null $resourceKey
     * @return array
     */
    public function collection($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->fractal->collection($data, $transformer, $resourceKey);
    }
}
