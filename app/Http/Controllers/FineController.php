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
use Illuminate\Http\Request;

class FineController extends Controller
{
    use ApiResponse;

    public function index(Request $request, ListFinesAction $action)
    {
        return $this->success(
            FineResource::collection($action->execute(FineFilterData::fromRequest($request))),
            'Fines retrieved successfully'
        );
    }

    public function store(FineRequest $request, CreateFineAction $action)
    {
        $fine = $action->execute(FineData::fromRequest($request));

        return $this->success(
            new FineResource($fine),
            'Fine created successfully',
            201
        );
    }

    public function show(int $id, ShowFineAction $action)
    {
        return $this->success(
            new FineResource($action->execute($id)),
            'Fine retrieved successfully'
        );
    }

    public function update(FineRequest $request, int $id, UpdateFineAction $action)
    {
        $fine = $action->execute($id, FineData::fromRequest($request));

        return $this->success(
            new FineResource($fine),
            'Fine updated successfully'
        );
    }

    public function destroy(int $id, DeleteFineAction $action)
    {
        $action->execute($id);

        return $this->success(null, 'Fine deleted successfully');
    }
}