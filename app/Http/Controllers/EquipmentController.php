<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentCollection;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Http\Requests\StoreEquipmentRequest;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Services\EquipmentService;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * @var EquipmentService
     */
    private $equipmentService;

    /**
     * EquipmentController constructor.
     * @param EquipmentService $equipmentService
     */
    public function __construct(EquipmentService $equipmentService)
    {
        $this->equipmentService = $equipmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $request->validate([
            'id' => 'integer|min:1',
            'equipment_type_id' => 'integer|min:1',
            'serial_number' => 'string',
            'note' => 'string',
        ]);

        $items = $this->equipmentService->getItems($request->all());
        return new EquipmentCollection($items);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEquipmentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEquipmentRequest $request)
    {
        return response()->json(['is_success' => $this->equipmentService->storeItems($request->all())]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipment $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        return response()->json(new EquipmentResource($equipment));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEquipmentRequest $request
     * @param  \App\Models\Equipment $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        return response()->json(['is_success' => $this->equipmentService->updateItem($equipment, $request->all())]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipment $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        return response()->json(['is_success' => $equipment && $this->equipmentService->deleteItem($equipment)]);
    }
}
