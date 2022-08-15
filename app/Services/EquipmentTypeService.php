<?php

namespace App\Services;

use App\Helpers\RegexHelper;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\Config;

class EquipmentTypeService
{
    /**
     * get item
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return EquipmentType::find($id);
    }

    /**
     * return paginated collection with filters
     *
     * @param array $data
     * @return EquipmentType[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getItems($data = [])
    {
        $query = EquipmentType::where('id', '>', 0);
        if (!empty($data['id'])) {
            $query->where('id', $data['id']);
        }
        if (!empty($data['name'])) {
            $query->where('name', $data['name']);
        }
        if (!empty($data['mask'])) {
            $query->where('mask', $data['mask']);
        }

        return $query->paginate(Config::get('pagination.equipment-type'));
    }

    /**
     * get all ids
     *
     * @return mixed
     */
    public function getIds()
    {
        return EquipmentType::pluck('id')->all();
    }

    /**
     * validate Serial Number
     *
     * @param $equipmentTypeId
     * @param $serialNumberArr
     * @return array
     */
    public function getInvalidSerialNumbers($equipmentTypeId, $serialNumberArr)
    {
        $equipmentTypeMask = $this->getItem($equipmentTypeId)->mask;

        $result = [];
        $pattern = RegexHelper::convertMaskToRegex($equipmentTypeMask);
        foreach ($serialNumberArr as $item) {
            if (!preg_match($pattern, $item, $matches)) {
                $result[] = $item;
            }
        }
        return $result;
    }
}
