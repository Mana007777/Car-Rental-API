<?php

namespace App\Actions\Maintenance;

use App\Exceptions\NotFoundException;
use App\Models\Maintenance;

class ShowMaintenanceAction
{
    public function execute(int $id): Maintenance
    {
        $maintenance = Maintenance::with(['car', 'employee'])->find($id);

        if (! $maintenance) {
            throw new NotFoundException('Maintenance not found');
        }

        return $maintenance;
    }
}