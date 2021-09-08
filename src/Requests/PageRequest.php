<?php
/**
 * Created by PhpStorm.
 * User: ariunbold
 * Date: 5/30/18
 * Time: 22:00
 */

namespace Lambda\Puzzle\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'name' => 'required|max:255|unique:agent_settings',
                        'value' => 'required',
                        'description' => ''
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name' => 'required|max:255' . Rule::unique('agent_settings', 'name')->ignore($this->id),
                        'value' => '',
                        'description' => ''
                    ];
                }
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'name.unique' => 'Хуудсын нэр давхардсан байна. Ялгаатай бичнэ үү'
        ];
    }
}
