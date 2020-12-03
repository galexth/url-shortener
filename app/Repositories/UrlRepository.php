<?php

namespace App\Repositories;

use App\Components\Decoder\DecoderInterface;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class UrlRepository
{
    protected DecoderInterface $decoder;

    private Url $model;

    public function __construct(DecoderInterface $decoder)
    {
        $this->model = new Url();
        $this->decoder = $decoder;
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Url|null
     */
    public function findById(int $id): ?Url
    {
        return Url::query()->find($id);
    }

    /**
     * @param string $url
     * @param bool $expired
     *
     * @return \App\Models\Url|null
     */
    public function findByUrl(string $url, bool $expired = false): ?Url
    {
        $query = Url::query()->where('url', $url);

        if ($expired) {
            $query->where('expires_at', '>', Carbon::now());
        }

        return $query->first();
    }

    /**
     * @param array $filter
     * @param array $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function find(array $filter = [], array $sort = []): Builder
    {
        $query = Url::query();

        if (! empty($filter['code'])) {
            $id = $this->decoder->decode($filter['code']);

            $query->where('id', $id);
        }

        if (! empty($filter['url'])) {
            $query->where('url', 'like', $filter['url']);
        }

        foreach ($sort as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    /**
     * @param array $data
     *
     * @return \App\Models\Url
     */
    public function create(array $data): Url
    {
        return Url::query()->create($data);
    }
}
