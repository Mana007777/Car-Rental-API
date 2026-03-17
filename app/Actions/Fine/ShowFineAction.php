<?php

namespace App\Actions\Fine;

use App\Exceptions\NotFoundException;
use App\Models\Fine;

class ShowFineAction
{
    public function execute(int $id): Fine
    {
        $fine = Fine::with('rental')->find($id);

        if (! $fine) {
            throw new NotFoundException('Fine not found');
        }

        return $fine;
    }
}