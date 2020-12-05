<?php

namespace App\Repositories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Builder;

interface UrlRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return \App\Models\Url|null
     */
    public function findById(int $id): ?Url;

    /**
     * @param string $code
     *
     * @return \App\Models\Url|null
     */
    public function findByCode(string $code): ?Url;

    /**
     * @param string $url
     * @param bool $expired
     *
     * @return \App\Models\Url|null
     */
    public function findByUrl(string $url, bool $expired = false): ?Url;

    /**
     * @param array $filter
     * @param array $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function find(array $filter = [], array $sort = []): Builder;

    /**
     * @param \App\Models\Url $url
     *
     * @return bool
     */
    public function delete(Url $url): bool;

    /**
     * @param array $data
     *
     * @return \App\Models\Url
     */
    public function create(array $data): Url;
}
