<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Vrati listu svih kategorija.
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::all();

        return CategoryResource::collection($categories);
    }
}
