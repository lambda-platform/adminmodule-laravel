<?php

namespace Lambda\Puzzle\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
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
        switch($this->method())
        {
            case 'GET':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'name' => 'required|min:3|max:35|unique:permissions,name',
                        'display_name' => 'required|min:3|max:35|unique:permissions,display_name',
                        'description' => 'max:100'
                    ];
                }
            case 'PUT':
            {
                return [];
            }
            case 'PATCH':
                {
                    return [
                        'name' => 'required|min:3|max:35'.Rule::unique('permissions', 'name')->ignore($this->id),
                        'display_name' => 'required|min:3|max:35'.Rule::unique('permissions', 'display_name')->ignore($this->id),
                        'description' => 'max:100',
                    ];
                }
            case 'DELETE':
                {
                    return [];
                }
            default:break;
        }
    }
    public function messages()
    {
        return [
            'name.required'    => 'Зөвшөөрлийн нэрээ заавал бичнэ үү!',
            'name.max'         => 'Зөвшөөрлийн нэр хамгийн ихдээ 35 тэмдэгтээс ихгүй байна!',
            'name.min'         => 'Зөвшөөрлийн нэр хамгийн багадаа 3 тэмдэгтээс багагүй байна!',
            'name.unique'         => 'Зөвшөөрлийн нэр өмнө нь үүссэн байна. Өөр нэр бичнэ үү!',
            'display_name.required'    => 'Зөвшөөрлийн харагдах нэрээ заавал бичнэ үү!',
            'display_name.max'         => 'Зөвшөөрлийн харагдах нэр хамгийн ихдээ 35 тэмдэгтээс ихгүй байна!',
            'display_name.min'         => 'Зөвшөөрлийн харагдах нэр хамгийн багадаа 3 тэмдэгтээс багагүй байна!',
            'display_name.unique'         => 'Зөвшөөрлийн харагдах нэр өмнө нь үүссэн байна. Өөр нэр бичнэ үү!',
            'description.max'         => 'Зөвшөөрлийн тайлбар хамгийн ихдээ 100 тэмдэгтээс ихгүй байна!',
        ];
    }
}
