<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Serializers\NoDataSerializer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected function jsonResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return $this->jsonResponse([
            'error' => $message,
            'code' => $code
        ], $code);
    }

    protected function showMessage($message, $code = Response::HTTP_OK)
    {
        return $this->jsonResponse([
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Generalización de las respuestas JSON exitosas para las colecciones de datos.
     */
    protected function showAll(Collection $collection, $code = Response::HTTP_OK)
    {
        if ($collection->isEmpty()) {
            return $this->jsonResponse($collection, $code);
        }

        if (isset($collection->first()->transformer)) {
            $transformer = $collection->first()->transformer;

            $collection = $this->filterData($collection, $transformer);
            $collection = $this->sortData($collection, $transformer);
            $collection = $this->paginateData($collection);
            $collection = $this->transformData($collection, $transformer);
            $collection = $this->cacheResponse($collection);
        }

        return $this->jsonResponse($collection, $code);
    }

    /**
     * Generalización de las respuestas JSON exitosas para un objeto de la colección.
     */
    protected function showOne(Model $model, $code = Response::HTTP_OK)
    {
        $transformer = $model->first() ? $model->first()->transformer : $model->transformer;
        $data = $this->transformData($model, $transformer);

        return $this->jsonResponse($data, $code);
    }

    /**
     * función para ordenar segun el campo sort_by de un request.
     */
    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            $attribute = $transformer::originalAttribute(request()->sort_by);

            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }

    /**
     * función para filtrar segun el campo enviado en el request.
     */
    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $values = explode('|', $value);
                $countValues = count($values);

                if ($countValues === 1) {
                    $collection = $collection->where($attribute, $value);
                } elseif ($countValues === 2) {
                    switch ($values[0]) {
                        case '>':
                            $collection = $collection->where($attribute, '>', $values[1]);
                            break;
                        case '<':
                            $collection = $collection->where($attribute, '<', $values[1]);
                            break;
                        case '>=':
                            $collection = $collection->where($attribute, '>=', $values[1]);
                            break;
                        case '<=':
                            $collection = $collection->where($attribute, '<=', $values[1]);
                            break;
                        case '!=':
                            $collection = $collection->where($attribute, '<>', $values[1]);
                            break;
                        case 'like':
                            $collection = $collection->reject(function ($model) use ($attribute, $values) {
                                return mb_strpos($model[$attribute], $values[1]) === false;
                            });
                            break;
                        case 'in':
                            $collection = $collection->whereIn($query, explode(',', $values[1]));
                            break;
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * función para la paginación.
     */
    protected function paginateData(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:100'
        ];
        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->query());

        return $paginated;
    }

    /**
     * función para la cache.
     */
    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 15 / 60, function () use ($data) {
            return $data;
        });
    }

    /**
     * Generalización de las transformaciones para las respuestas JSON.
     */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer)
            ->serializeWith(new NoDataSerializer());

        if (isset($_GET['include']))
            $transformation->parseIncludes($_GET['include']);

        return $transformation->toArray();
    }
}
