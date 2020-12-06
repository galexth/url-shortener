<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UrlController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Repositories\UrlRepositoryInterface $repository
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UrlRepositoryInterface $repository): Response
    {
        $collection = $repository->find(
            $request->input('filters', []),
            $request->input('sortBy', [])
        )
        ->paginate($request->input('pageSize'));

        return response($collection);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Repositories\UrlRepositoryInterface $repository
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, UrlRepositoryInterface $repository): Response
    {
        $this->validate($request, [
            'url' => 'required|blacklist|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'expires_at' => 'nullable|date|after:now',
        ], [
            'blacklist' => 'The url is blacklisted.'
        ]);

        if (! $url = $repository->findByUrl($request->input('url'))) {
            $url = $repository->create($request->input());
        }

        return response($url, $url->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * @param $id
     * @param \App\Repositories\UrlRepositoryInterface $repository
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, UrlRepositoryInterface $repository): Response
    {
        if (! $url = $repository->findById($id)) {
            return response(['error' => 'Model not found.'], 404);
        }

        return response(['deleted' => $repository->delete($url)]);
    }
}
