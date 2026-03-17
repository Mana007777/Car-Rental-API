<?php

namespace App\Actions\Branch;

use App\Exceptions\NotFoundException;
use App\Models\Branch;

class DeleteBranchAction
{
    public function execute(int $id): void
    {
        $branch = Branch::find($id);

        if (! $branch) {
            throw new NotFoundException('Branch not found');
        }

        $branch->delete();
    }
}