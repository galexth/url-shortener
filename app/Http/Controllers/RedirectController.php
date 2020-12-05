<?php

namespace App\Http\Controllers;

use App\Components\Decoder\ParserException;
use App\Repositories\UrlRepositoryInterface;

class RedirectController extends Controller
{
    /**
     * @param $code
     * @param \App\Repositories\UrlRepositoryInterface $repository
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function forward($code, UrlRepositoryInterface $repository)
    {
        try {
            $url = $repository->findByCode($code);
        } catch (ParserException $e) {
            return response(['error' => $e->getMessage()], 410);
        }

        if (! $url || $url->isExpired()) {
            return response(['error' => 'Url not found.'], 410);
        }

        $url->increment('hits');

        return redirect($url->url, 302);
    }
}
