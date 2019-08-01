<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Factory as ValidationFactory;
use App\Http\Controllers\EmployeeController;

class EditEmployee extends FormRequest
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
        return [
            'name' => 'required|string|between:2,256',
            'employee_date' => 'date_format:d.m.y',
            'email' => 'email',
            'salary' => 'numeric|between:0,500',
            'photo' => 'file|mimes:jpeg,png|max:5000'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'salary.numeric' => 'The salary must be a number with "." separator instead of ",".',
        ];
    }
}
