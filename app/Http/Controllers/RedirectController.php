<?php

namespace App\Http\Controllers;

use App\Components\Decoder\DecoderInterface;
use App\Components\Decoder\ParserException;
use App\Repositories\UrlRepository;

class RedirectController extends Controller
{
    /**
     * @param $code
     * @param \App\Repositories\UrlRepository $repository
     * @param \App\Components\Decoder\DecoderInterface $decoder
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function forward($code, UrlRepository $repository, DecoderInterface $decoder)
    {
        try {
            $id = $decoder->decode($code);
        } catch (ParserException $e) {
            return response(['error' => $e->getMessage()], 400);
        }

        if (! $url = $repository->findById($id)) {
            return response(['error' => 'Url not found.'], 410);
        }

        if ($url->isExpired()) {
            return response(['error' => 'Url has expired.'], 410);
        }

        $url->increment('hits');

        return redirect($url->url, 302);
    }
}
