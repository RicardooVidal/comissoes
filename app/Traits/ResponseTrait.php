<?php

namespace App\Traits;

trait ResponseTrait
{
    /**
     * Retorna success
     * 
     * @param mixed $content
     * @param int $code
     */
    public function success($content, $code = 200)
    {
        return $this->defaultResponse('success', $code, $content);
    }

    /**
     * Retorna error
     * 
     * @param mixed $content
     * @param int $code
     */
    public function error($content, $code = 500)
    {
        return $this->defaultResponse('error', $code, $content);
    }

    /**
     * Resposta padrão
     * 
     * @param string $status
     * @param int $code
     * @param mixed $content
     */
    public function defaultResponse($status, $code, $content)
    {
        return response([
            'status' => $status,
            'code' => $code,
            'result' => $content
        ], $code);
    }
}