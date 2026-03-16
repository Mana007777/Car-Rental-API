<?php

namespace App\Actions\Maintenance;

use App\Models\Maintenance;

class ShowMaintenanceAction
{
    public function execute(int $id): Maintenance
    {
        return Maintenance::with(['car', 'employee'])->findOrFail($id);
    }
}