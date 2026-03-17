<?php

namespace App\Http\Controllers;

use App\Actions\Branch\CreateBranchAction;
use App\Actions\Branch\DeleteBranchAction;
use App\Actions\Branch\ListBranchesAction;
use App\Actions\Branch\ShowBranchAction;
use App\Actions\Branch\UpdateBranchAction;
use App\DTOs\Branch\BranchData;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchController extends Controller
{
    public function index(ListBranchesAction $action)
    {
        return BranchResource::collection($action->execute());
    }

    public function store(StoreBranchRequest $request, CreateBranchAction $action)
    {
        return new BranchResource(
            $action->execute(BranchData::fromRequest($request))
        );
    }

    public function show(int $id, ShowBranchAction $action)
    {
        return new BranchResource($action->execute($id));
    }

    public function update(UpdateBranchRequest $request, int $id, UpdateBranchAction $action)
    {
        return new BranchResource(
            $action->execute($id, BranchData::fromRequest($request))
        );
    }

    public function destroy(int $id, DeleteBranchAction $action)
    {
        $action->execute($id);

        return response()->json([
            'message' => 'Branch deleted successfully'
        ]);
    }
}