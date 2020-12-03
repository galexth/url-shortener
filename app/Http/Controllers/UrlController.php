<?php

namespace App\Http\Controllers;

use App\Repositories\UrlRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UrlController extends Controller
{
    public function index(Request $request, UrlRepository $repository): Response
    {
        $collection = $repository->find(
            $request->input('filter', []),
            $request->input('sort', [])
        )->get();

        return response($collection);
    }

    public function store(Request $request, UrlRepository $repository): Response
    {
        $this->validate($request, [
            'url' => 'required|url',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if (! $url = $repository->findByUrl($request->input('url'))) {
            $url = $repository->create($request->input());
        }

        return response($url, $url->wasRecentlyCreated ? 201 : 200);
    }

    public function destroy($id, UrlRepository $repository): Response
    {
        if (! $url = $repository->findById($id)) {
            return response(['error' => 'Url not found.'], 410);
        }

        return response(['deleted' => $url->delete()]);
    }
}
