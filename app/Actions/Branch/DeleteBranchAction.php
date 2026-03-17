<?php

namespace App\Actions\Branch;

use App\Models\Branch;

class DeleteBranchAction
{
    public function execute(int $id): void
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
    }
}