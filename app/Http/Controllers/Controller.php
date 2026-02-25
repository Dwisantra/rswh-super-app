<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Fungsi standar untuk response API
     */
    protected function apiResponse($data = null, int $status = 200, bool $success = true)
    {
        if (is_array($data) && isset($data['total']) && isset($data['data'])) {
            return response()->json([
                'success' => $success,
                'status'  => $status,
                'total'   => $data['total'],
                'data'    => $data['data'],
            ], $status);
        }

        $formattedData = $data ?: [];

        if (is_array($formattedData) && count($formattedData) > 0 && !isset($formattedData[0])) {
            $formattedData = [$formattedData];
        } elseif (!is_array($formattedData) && !is_null($formattedData)) {
            $formattedData = [$formattedData];
        }

        return response()->json([
            'success' => $success,
            'status'  => $status,
            'total'   => is_array($formattedData) ? count($formattedData) : 0,
            'data'    => $formattedData,
        ], $status);
    }
}
