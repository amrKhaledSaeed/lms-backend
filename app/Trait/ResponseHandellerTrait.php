<?php
namespace App\Trait;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ResponseHandellerTrait
{
    public function apiResponseJson(
        string $message,
        $data = [],
        int $statusCode = Response::HTTP_OK,
        ?string $resourceClass = null
    ) {
        $formattedData = null;
        $pagination    = null;
        if ($data === null && $data !== false) {
            return response()->json([
                'message'    => $message ?? 'No Data Found',
                'data'       => [],
                'statusCode' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        }elseIf($data === false)
        {
            return response()->json([
                'message'    => 'No Data Found',
                'data'       => [],
                'statusCode' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_OK);
        }

        if ($resourceClass && class_exists($resourceClass)) {        

            if ($data instanceof \Illuminate\Contracts\Pagination\Paginator) {
                $pagination =$this->paginationArray($data);
            
                $formattedData = $resourceClass::collection($data)->resolve();
            } elseif ($data instanceof \Illuminate\Database\Eloquent\Collection || is_iterable($data)) {
                $formattedData = $resourceClass::collection($data)->resolve();
            } else {
                $formattedData = (new $resourceClass($data))->resolve();
            }
        } elseif ($data instanceof JsonResource || $data instanceof ResourceCollection) {
            $formattedData = $data->resolve();
        } else {
            $formattedData = $data;
        }

        $response = [
            'message'    => $message,
            'data'       => $formattedData,
            'statusCode' => $statusCode,
        ];

        if ($pagination) {
            $response['pagination'] = $pagination;
        }

        return response()->json($response, $statusCode);
    }

    public function paginationArray($data)
    {
        return [
            'total'         => $data->total(),
            'per_page'      => $data->perPage(),
            'current_page'  => $data->currentPage(),
            'last_page'     => $data->lastPage(),
            'from'          => $data->firstItem(),
            'to'            => $data->lastItem(),
            'first_page_url'=> $data->url(1),
            'last_page_url' => $data->url($data->lastPage()),
            'next_page_url' => $data->nextPageUrl(),
            'prev_page_url' => $data->previousPageUrl(),
        ];
    }
}

