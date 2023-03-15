<?php

namespace App\Services\Backoffice;

use Illuminate\Support\Collection;

class CategoryService extends BaseService
{
    protected $basePath = '/categories';

    public function all(string $keyword = '', Int $perPage = 25, Int $page = 1): Collection | null
    {
        $response = $this->client()->get($this->basePath, [
            'q'     => $keyword,
            'per'   => $perPage,
            'page'  => $page
        ]);


        if ($response->failed()) {
            return null;
        }

        $categories = $response->json();

        return collect($categories['data']);
    }

    public function getById($id): Collection | null
    {
        $url = "{$this->basePath}/{$id}";

        return cache()->remember("backoffice.category.${id}", 60 * 60 * 10, function () use ($url) {
            $response = $this->client()->get($url);

            if ($response->failed()) {
                return null;
            }

            return collect($response->json());
        });
    }
}
