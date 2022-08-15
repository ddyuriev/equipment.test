<?php

namespace App\Services;

use App\Models\Equipment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class EquipmentService
{
    /**
     * return paginated collection with filters
     *
     * @param $data
     * @return mixed
     */
    public function getItems($data)
    {
        $query = Equipment::where('id', '>', 0);
        if (!empty($data['id'])) {
            $query->where('id', $data['id']);
        }
        if (!empty($data['equipment_type_id'])) {
            $query->where('equipment_type_id', $data['equipment_type_id']);
        }
        if (!empty($data['serial_number'])) {
            $query->where('serial_number', $data['serial_number']);
        }
        if (!empty($data['note'])) {
            $query->where('note', $data['note']);
        }

        return $query->paginate(Config::get('pagination.equipment'));
    }

    /**
     * store item/items
     *
     * @param $data
     * @return bool
     */
    public function storeItems($data)
    {
        $insertData = [];
        foreach ($data['serial_number'] as $serialNumber) {
            $insertData[] = [
                'equipment_type_id' => $data['equipment_type_id'],
                'serial_number' => $serialNumber,
                'note' => $data['note'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        Equipment::insert($insertData);

        return true;
    }


    /**
     * update item
     *
     * @param $equipment
     * @param $data
     * @return bool
     */
    public function updateItem($equipment, $data)
    {
        $equipment->update($data);

        return true;
    }

    /**
     * delete item
     *
     * @param $equipment
     * @return bool
     */
    public function deleteItem($equipment)
    {
        $equipment->delete();

        return true;
    }
}
