<?php

namespace App\Actions\Branch;

use App\DTOs\Branch\BranchData;
use App\Models\Branch;

class CreateBranchAction
{
    public function execute(BranchData $data): Branch
    {
        return Branch::create($data->toArray());
    }
}