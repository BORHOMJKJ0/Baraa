<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'https://via.placeholder.com')
                ? $this->image
                : config('app.url').'/storage/'.$this->image)
            : null;

        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'location' => $this->location,
            'email' => $this->email,
            'image' => $imageUrl,
        ];
    }
}
