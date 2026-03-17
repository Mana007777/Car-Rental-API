<?php

namespace App\Http\Controllers;

use App\Actions\Maintenance\CreateMaintenanceAction;
use App\Actions\Maintenance\DeleteMaintenanceAction;
use App\Actions\Maintenance\GetCarMaintenancesAction;
use App\Actions\Maintenance\ListCarMaintenancesAction;
use App\Actions\Maintenance\ShowMaintenanceAction;
use App\Actions\Maintenance\UpdateMaintenanceAction;
use App\DTOs\Maintenance\MaintenanceData;
use App\Http\Requests\MaintenanceRequest;
use App\Http\Resources\MaintenanceResource;
use App\Traits\ApiResponse;

class MaintenanceController extends Controller
{
    use ApiResponse;

    public function index(int $carId, GetCarMaintenancesAction $action)
    {
        return $this->success(
            MaintenanceResource::collection($action->execute($carId)),
            'Maintenances retrieved successfully'
        );
    }

    public function store(MaintenanceRequest $request, int $carId, CreateMaintenanceAction $action)
    {
        $maintenance = $action->execute($carId, MaintenanceData::fromRequest($request));

        return $this->success(
            new MaintenanceResource($maintenance),
            'Maintenance created successfully',
            201
        );
    }

    public function show(int $id, ShowMaintenanceAction $action)
    {
        return $this->success(
            new MaintenanceResource($action->execute($id)),
            'Maintenance retrieved successfully'
        );
    }

    public function update(MaintenanceRequest $request, int $id, UpdateMaintenanceAction $action)
    {
        $maintenance = $action->execute($id, MaintenanceData::fromRequest($request));

        return $this->success(
            new MaintenanceResource($maintenance),
            'Maintenance updated successfully'
        );
    }

    public function destroy(int $id, DeleteMaintenanceAction $action)
    {
        $action->execute($id);

        return $this->success(null, 'Maintenance deleted successfully');
    }
}