<?php

namespace App\Http\Requests;

use App\Services\EquipmentTypeService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEquipmentRequest extends FormRequest
{
    /**
     * Store serial number, which don't correspond equipment_type_id
     *
     * @var
     */
    public $invalidNewSerialNumber;

    /**
     * Store existing serial number, which don't correspond equipment_type_id
     *
     * @var
     */
    public $invalidOldSerialNumber;

    /**
     * @var EquipmentTypeService
     */
    private $equipmentTypeService;


    /**
     * StoreEquipmentRequest constructor.
     * @param EquipmentTypeService $equipmentTypeService
     */
    public function __construct(EquipmentTypeService $equipmentTypeService)
    {
        $this->equipmentTypeService = $equipmentTypeService;
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'equipment_type_id' => [
                'numeric',
                'in:' . join(',', $this->equipmentTypeService->getIds()),
                function ($attribute, $value, $fail) {
                    $newSerialNumber = $this->get('serial_number');
                    if (!empty($newSerialNumber)) {
                        $this->invalidNewSerialNumber = $this->equipmentTypeService->getInvalidSerialNumbers($value, [$newSerialNumber]);
                    } else {
                        $currentEquipmentSerialNumber = $this->route('equipment')->serial_number;
                        $this->invalidOldSerialNumber = $this->equipmentTypeService->getInvalidSerialNumbers($value, [$currentEquipmentSerialNumber]);
                    }
                },
            ],
            'serial_number' => [
                'unique:equipment,serial_number',
                function ($attribute, $value, $fail) {
                    $equipmentTypeId = $this->get('equipment_type_id');
                    if (empty($equipmentTypeId)) {
                        $this->invalidNewSerialNumber = $this->equipmentTypeService->getInvalidSerialNumbers($this->route('equipment')->equipment_type_id, [$value]);
                    }
                },
            ],
            'note' => ['string']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!empty($this->invalidNewSerialNumber)) {
                $validator->errors()->add('serial_number', ['equipment_type_id don\'t correspond with' => $this->invalidNewSerialNumber]);
            }
            if (!empty($this->invalidOldSerialNumber)) {
                $validator->errors()->add('serial_number', ['equipment_type_id don\'t correspond with' => $this->invalidOldSerialNumber]);
            }
        });
    }
}
