<?php

namespace App\Http\Controllers;

use App\Actions\Fine\CreateFineAction;
use App\Actions\Fine\DeleteFineAction;
use App\Actions\Fine\ListFinesAction;
use App\Actions\Fine\ShowFineAction;
use App\Actions\Fine\UpdateFineAction;
use App\DTOs\Fine\FineData;
use App\DTOs\Fine\FineFilterData;
use App\Http\Requests\FineRequest;
use App\Http\Resources\FineResource;
use App\Traits\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FineController extends Controller
{
    use ApiResponse;

    public function index(Request $request, ListFinesAction $action)
    {
        $fines = $action->execute(FineFilterData::fromRequest($request));

        return $this->success(
            FineResource::collection($fines),
            'Fines retrieved successfully'
        );
    }

    public function store(FineRequest $request, CreateFineAction $action)
    {
        try {
            $fine = $action->execute(FineData::fromRequest($request));

            return $this->success(
                new FineResource($fine),
                'Fine created successfully',
                201
            );
        } catch (ModelNotFoundException) {
            return $this->error('Rental not found', 404);
        }
    }

    public function show(int $id, ShowFineAction $action)
    {
        try {
            return $this->success(
                new FineResource($action->execute($id)),
                'Fine retrieved successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Fine not found', 404);
        }
    }

    public function update(FineRequest $request, int $id, UpdateFineAction $action)
    {
        try {
            $fine = $action->execute($id, FineData::fromRequest($request));

            return $this->success(
                new FineResource($fine),
                'Fine updated successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->error('Fine or rental not found', 404);
        }
    }

    public function destroy(int $id, DeleteFineAction $action)
    {
        try {
            $action->execute($id);

            return $this->success(null, 'Fine deleted successfully');
        } catch (ModelNotFoundException) {
            return $this->error('Fine not found', 404);
        }
    }
}