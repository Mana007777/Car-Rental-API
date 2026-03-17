<?php

namespace App\Actions\Branch;

use App\DTOs\Branch\BranchData;
use App\Models\Branch;

class UpdateBranchAction
{
    public function execute(int $id, BranchData $data): Branch
    {
        $branch = Branch::findOrFail($id);
        $branch->update($data->toArray());

        return $branch->fresh();
    }
}