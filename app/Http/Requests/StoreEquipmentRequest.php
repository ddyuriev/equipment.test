<?php

namespace App\Http\Requests;

use App\Models\Equipment;
use App\Services\EquipmentTypeService;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
{
    /**
     * Store array of serial number, which fails mask validation
     *
     * @var
     */
    public $invalidSerialNumbersByMask;

    /**
     * Store array of serial number, which fails unique validation
     *
     * @var
     */
    public $invalidSerialNumberUnique;

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
            'equipment_type_id' => ['required', 'numeric', 'in:' . join(',', $this->equipmentTypeService->getIds())],
            'serial_number' => [
                'required',
                'array',
                function ($attribute, $value) {
                    $this->invalidSerialNumbersByMask = $this->equipmentTypeService->getInvalidSerialNumbers($this->get('equipment_type_id'), $value);
                    $this->invalidSerialNumberUnique = Equipment::whereIn('serial_number', $value)->pluck('serial_number')->all();
                },
            ],
            'note' => ['required', 'string']
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!empty($this->invalidSerialNumbersByMask)) {
                $validator->errors()->add('serial_number', ['mask validation error' => $this->invalidSerialNumbersByMask]);
            }
            if (!empty($this->invalidSerialNumberUnique)) {
                $validator->errors()->add('serial_number', ['These serial number has already been taken' => $this->invalidSerialNumberUnique]);
            }
        });
    }
}
