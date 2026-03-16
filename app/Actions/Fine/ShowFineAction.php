<?php

namespace App\Actions\Fine;

use App\Models\Fine;

class ShowFineAction
{
    public function execute(int $id): Fine
    {
        return Fine::with('rental')->findOrFail($id);
    }
}