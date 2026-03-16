<?php

namespace App\Http\Controllers;

use App\Actions\Maintenance\CreateMaintenanceAction;
use App\Actions\Maintenance\DeleteMaintenanceAction;
use App\Actions\Maintenance\GetCarMaintenancesAction;
use App\Actions\Maintenance\ShowMaintenanceAction;
use App\Actions\Maintenance\UpdateMaintenanceAction;
use App\DTOs\Maintenance\MaintenanceData;
use App\Http\Requests\MaintenanceRequest;
use App\Http\Resources\MaintenanceResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MaintenanceController extends Controller
{
    use ApiResponse;

    public function index(int $carId, GetCarMaintenancesAction $action)
    {
        try {
            $maintenances = $action->execute($carId);

            return $this->success(
                MaintenanceResource::collection($maintenances),
                'Maintenances retrieved successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Car not found', 404);
        }
    }

    public function store(
        MaintenanceRequest $request,
        int $carId,
        CreateMaintenanceAction $action
    ) {
        try {
            $maintenance = $action->execute($carId, MaintenanceData::fromRequest($request));

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance created successfully',
                201
            );
        } catch (ModelNotFoundException) {
            return $this->error('Car not found', 404);
        }
    }

    public function show(int $id, ShowMaintenanceAction $action)
    {
        try {
            return $this->success(
                new MaintenanceResource($action->execute($id)),
                'Maintenance retrieved successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Maintenance not found', 404);
        }
    }

    public function update(
        MaintenanceRequest $request,
        int $id,
        UpdateMaintenanceAction $action
    ) {
        try {
            $maintenance = $action->execute($id, MaintenanceData::fromRequest($request));

            return $this->success(
                new MaintenanceResource($maintenance),
                'Maintenance updated successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Maintenance not found', 404);
        }
    }

    public function destroy(int $id, DeleteMaintenanceAction $action)
    {
        try {
            $action->execute($id);

            return $this->success(null, 'Maintenance deleted successfully');
        } catch (ModelNotFoundException) {
            return $this->error('Maintenance not found', 404);
        }
    }
}