<?php

namespace App\Http\Resources\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'https://via.placeholder.com')
                ? $this->image
                : config('app.url').'/storage/'.$this->image)
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $imageUrl,
            'address' => $this->address,
            'user' => $this->user->first_name.' '.$this->user->last_name,
        ];
    }
}
