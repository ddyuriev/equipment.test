<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentTypeCollection;
use App\Services\EquipmentTypeService;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    /**
     * @var EquipmentTypeService
     */
    private $equipmentTypeService;

    /**
     * EquipmentTypeController constructor.
     * @param EquipmentTypeService $equipmentTypeService
     */
    public function __construct(EquipmentTypeService $equipmentTypeService)
    {
        $this->equipmentTypeService = $equipmentTypeService;
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
            'name' => 'string',
            'mask' => 'string',
        ]);

        $items = $this->equipmentTypeService->getItems($request->all());
        return new EquipmentTypeCollection($items);
    }
}
