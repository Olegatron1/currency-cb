<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
		return [
			'date' => $this->date,
			'quote_currency' => $this->quote_currency,
			'base_currency' => $this->base_currency,
			'rate' => round($this->rate, 4),
		];
    }
}
