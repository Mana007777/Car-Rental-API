<?php

namespace App\Actions\Fine;

use App\DTOs\Fine\FineFilterData;
use App\Models\Fine;

class ListFinesAction
{
    public function execute(FineFilterData $filters)
    {
        return Fine::with('rental')
            ->when($filters->paid !== null, fn ($q) => $q->where('paid', $filters->paid))
            ->when($filters->rental_id !== null, fn ($q) => $q->where('rental_id', $filters->rental_id))
            ->latest()
            ->get();
    }
}