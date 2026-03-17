<?php

namespace App\Actions\Branch;

use App\Exceptions\NotFoundException;
use App\Models\Branch;

class ShowBranchAction
{
    public function execute(int $id): Branch
    {
        $branch = Branch::find($id);

        if (! $branch) {
            throw new NotFoundException('Branch not found');
        }

        return $branch;
    }
}