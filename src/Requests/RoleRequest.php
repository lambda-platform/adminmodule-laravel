<?php

namespace Lambda\Puzzle\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
//                    'name' => 'required|min:3|max:35|unique:roles,name',
//                    'display_name' => 'required|min:3|max:35|unique:roles,display_name',
//                    'description' => 'max:100',
//                    'permissions' => 'required|array'
                ];
            }
            case 'PUT':
            {
                return [];
            }
            case 'PATCH':
            {
                return [
                    'name' => 'required|min:3|max:35'.Rule::unique('roles', 'name')->ignore($this->id),
                    'display_name' => 'required|min:3|max:35'.Rule::unique('roles', 'display_name')->ignore($this->id),
                    'description' => 'max:100',
                    'permissions' => 'required|array'
                ];
            }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'name.required'    => 'Үүргийн нэрээ заавал бичнэ үү!',
            'name.max'         => 'Үүргийн нэр хамгийн ихдээ 35 тэмдэгтээс ихгүй байна!',
            'name.min'         => 'Үүргийн нэр хамгийн багадаа 3 тэмдэгтээс багагүй байна!',
            'name.unique'         => 'Үүргийн нэр өмнө нь үүссэн байна. Өөр нэр бичнэ үү!',
            'display_name.required'    => 'Үүргийн харагдах нэрээ заавал бичнэ үү!',
            'display_name.max'         => 'Үүргийн харагдах нэр хамгийн ихдээ 35 тэмдэгтээс ихгүй байна!',
            'display_name.min'         => 'Үүргийн харагдах нэр хамгийн багадаа 3 тэмдэгтээс багагүй байна!',
            'display_name.unique'         => 'Үүргийн харагдах нэр өмнө нь үүссэн байна. Өөр нэр бичнэ үү!',
            'permissions.required'    => 'Үүргийн Зөвшөөрлүүдээс заавал сонгоно уу!',
        ];
    }
}
