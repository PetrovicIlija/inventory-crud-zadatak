<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /**
     * Vrati listu svih artikala.
     *
     * Pogledaj CategoryController@index kao primjer.
     *
     * Koraci:
     * 1. Dohvati sve artikle iz baze, zajedno s relacijom 'category' (koristi with())
     * 2. Vrati rezultat kao ArticleResource kolekciju (ArticleResource::collection(...))
     */
    public function index(): AnonymousResourceCollection
    {
        // TODO
    }

    /**
     * Spremi novi artikl u bazu.
     *
     * Koraci:
     * 1. Kreiraj novi Article model iz validiranih podataka iz $request (koristi $request->validated())
     *    - Article::create([...]) sprema model i vraća instancu
     * 2. Učitaj relaciju 'category' na kreiranom modelu (koristi $article->load('category'))
     * 3. Vrati novi artikl kao ArticleResource s HTTP statusom 201 (Created)
     *    - return (new ArticleResource($article))->response()->setStatusCode(201);
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        // TODO
    }

    /**
     * Ažuriraj postojeći artikl.
     *
     * Laravel automatski pronađe Article po ID-u iz URL-a (Route Model Binding).
     *
     * Koraci:
     * 1. Popuni model s validiranim podacima iz $request (koristi $article->fill($request->validated()))
     * 2. Spremi promjene (koristi $article->save())
     * 3. Učitaj relaciju 'category' (koristi $article->load('category'))
     * 4. Vrati ažurirani artikl kao ArticleResource
     */
    public function update(ArticleRequest $request, Article $article): ArticleResource
    {
        // TODO
    }

    /**
     * Obriši artikl (soft delete).
     *
     * Koraci:
     * 1. Obriši artikl (koristi $article->delete())
     *    - Soft delete samo postavi deleted_at timestamp, ne briše iz baze
     * 2. Vrati prazan odgovor s HTTP statusom 204 (No Content)
     *    - return response()->noContent();
     */
    public function destroy(Article $article): Response
    {
        // TODO
    }
}
