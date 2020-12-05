<?php

namespace App\Repositories;

use App\Components\Decoder\DecoderInterface;
use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class UrlRepository implements UrlRepositoryInterface
{
    private DecoderInterface $decoder;

    private Url $model;

    private bool $useCache;

    /**
     * UrlRepository constructor.
     *
     * @param \App\Components\Decoder\DecoderInterface $decoder
     * @param bool $useCache
     */
    public function __construct(Url $url, DecoderInterface $decoder, bool $useCache = true)
    {
        $this->model = $url;
        $this->decoder = $decoder;
        $this->useCache = $useCache;
    }

    /**
     * @param int $id
     *
     * @return \App\Models\Url|null
     */
    public function findById(int $id): ?Url
    {
        return $this->model->query()->find($id);
    }

    /**
     * @param string $code
     *
     * @return \App\Models\Url|null
     */
    public function findByCode(string $code): ?Url
    {
        if ($this->useCache && $url = Cache::get($code)) {
            return $url;
        }

        $url = $this->findById($this->decoder->decode($code));

        if ($this->useCache) {
            Cache::put($code, $url, $url->expires_at ?: Carbon::now()->addWeek());
        }

        return $url;
    }

    /**
     * @param string $url
     * @param bool $expired
     *
     * @return \App\Models\Url|null
     */
    public function findByUrl(string $url, bool $expired = false): ?Url
    {
        $query = $this->model->query()->where('url', $url);

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
        $query = $this->model->query();

        if (! empty($filter['short_code'])) {
            $id = $this->decoder->decode($filter['short_code']);

            $query->where('id', $id);
        }

        if (! empty($filter['url'])) {
            $query->where('url', 'like', "%{$filter['url']}%");
        }

        foreach ($sort as $item) {
            $query->orderBy($item['id'], $item['desc'] ? 'desc' : 'asc');
        }

        return $query;
    }

    /**
     * @param \App\Models\Url $url
     *
     * @return bool
     */
    public function delete(Url $url): bool
    {
        if ($this->useCache) {
            Cache::forget($url->short_code);
        }

        return $url->delete();
    }

    /**
     * @param array $data
     *
     * @return \App\Models\Url
     */
    public function create(array $data): Url
    {
        return $this->model->query()->create($data);
    }
}
