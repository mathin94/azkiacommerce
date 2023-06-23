<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'store_name' => $this->store_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_branch' => $this->is_branch,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->full_main_address,
        ];
    }
}
