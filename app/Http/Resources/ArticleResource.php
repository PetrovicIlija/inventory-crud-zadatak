<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'quantity' => $this->quantity,
            'price'    => $this->price,
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
