<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineResource extends JsonResource
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
            'rental_id' => $this->rental_id,
            'amount' => $this->amount,
            'reason' => $this->reason,
            'paid' => $this->paid,
            'fine_date' => $this->fine_date?->toDateString(),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),

            'rental' => $this->whenLoaded('rental', function () {
                return [
                    'id' => $this->rental->id,
                ];
            }),
        ];
    }
}