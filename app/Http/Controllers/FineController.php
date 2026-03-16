<?php

namespace App\Http\Controllers;

use App\Http\Requests\FineRequest;
use App\Http\Resources\FineResource;
use App\Models\Fine;
use App\Models\Rental;
use App\Traits\ApiResponse;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class FineController extends Controller
{
    use ApiResponse, Auditable;

    public function index(Request $request)
    {
        $query = Fine::with('rental')->latest();

        if ($request->filled('paid')) {
            $query->where('paid', filter_var($request->paid, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('rental_id')) {
            $query->where('rental_id', $request->rental_id);
        }

        $fines = $query->get();

        return $this->success(
            FineResource::collection($fines),
            'Fines retrieved successfully'
        );
    }

    public function store(FineRequest $request)
    {
        $rental = Rental::find($request->rental_id);

        if (!$rental) {
            return $this->error('Rental not found', 404);
        }

        $fine = Fine::create($request->fineData());
        $fine->load('rental');

        $this->logAudit(
            'created',
            'fines',
            $fine->id,
            'Fine created',
            null,
            $fine->toArray()
        );

        return $this->success(
            new FineResource($fine),
            'Fine created successfully',
            201
        );
    }

    public function show($id)
    {
        $fine = Fine::with('rental')->find($id);

        if (!$fine) {
            return $this->error('Fine not found', 404);
        }

        return $this->success(
            new FineResource($fine),
            'Fine retrieved successfully'
        );
    }

    public function update(FineRequest $request, $id)
    {
        $fine = Fine::with('rental')->find($id);

        if (!$fine) {
            return $this->error('Fine not found', 404);
        }

        $rental = Rental::find($request->rental_id);

        if (!$rental) {
            return $this->error('Rental not found', 404);
        }

        $oldValues = $fine->toArray();

        $fine->update($request->fineData());
        $fine->load('rental');

        $this->logAudit(
            'updated',
            'fines',
            $fine->id,
            'Fine updated',
            $oldValues,
            $fine->fresh()->toArray()
        );

        return $this->success(
            new FineResource($fine),
            'Fine updated successfully'
        );
    }

    public function destroy($id)
    {
        $fine = Fine::find($id);

        if (!$fine) {
            return $this->error('Fine not found', 404);
        }

        $oldValues = $fine->toArray();

        $fine->delete();

        $this->logAudit(
            'deleted',
            'fines',
            $id,
            'Fine deleted',
            $oldValues,
            null
        );

        return $this->success(
            null,
            'Fine deleted successfully'
        );
    }
}