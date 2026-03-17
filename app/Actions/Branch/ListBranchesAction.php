<?php

namespace App\Actions\Branch;

use App\Models\Branch;

class ListBranchesAction
{
    public function execute()
    {
        return Branch::all();
    }
}