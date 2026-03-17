<?php

namespace App\Actions\Branch;

use App\Models\Branch;

class ShowBranchAction
{
    public function execute(int $id): Branch
    {
        return Branch::findOrFail($id);
    }
}