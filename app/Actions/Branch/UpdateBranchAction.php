<?php

namespace App\Actions\Branch;

use App\DTOs\Branch\BranchData;
use App\Exceptions\NotFoundException;
use App\Models\Branch;

class UpdateBranchAction
{
    public function execute(int $id, BranchData $data): Branch
    {
        $branch = Branch::find($id);

        if (! $branch) {
            throw new NotFoundException('Branch not found');
        }

        $branch->update($data->toArray());

        return $branch->fresh();
    }
}