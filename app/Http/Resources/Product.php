<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        // return parent::toArray($request);
        return[
            'type' => "products",
            'id' => $this->id,
            'attributes' => [
            'name' => $this->name,
            'price' => $this->price,
            ],
        'links' =>[
            'self' => "http://127.0.0.1:8000/api/products/".$this->id
        ],
            
        ];
    }
}
