<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routes = ['register'];
        //Store rules
        $rules = [
            'f_name' => ['required', 'string', 'alpha', 'max:50'],
            'l_name' => ['required', 'string', 'alpha', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50'],
            'password' => [ 'required', 'string', 'max:20', 'confirmed',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('The :attribute must contain at least one uppercase letter.');
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail('The :attribute must contain at least one lowercase letter.');
                    }
                    if (!preg_match('/\d/', $value)) {
                        $fail('The :attribute must contain at least one number.');
                    }
                    if (!preg_match('/[\W_]/', $value)) {
                        $fail('The :attribute must contain at least one special character.');
                    }
                }
            ],
            'password_confirmation' => ['required', 'string', 'max:20'],
            'mobile' => ['required', 'string', 'max:10', 'regex:/^\d{10}$/', ],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female'],
            'address' => ['nullable', 'string', 'max:200'],
        ];

        if (request()->route()->getName() == 'profile.update') {
            //Update rules
            unset($rules['password']);
            unset($rules['password_confirmation']);     
            $rules['profile_pic']  = ['nullable','image', 'min:100', 'max:1024','mimes:png,jpg,jpeg'];  
        }
       
        return  $rules;
    }

    public function messages()
    {
        return [
            'password.regex' => 'The :attribute must meet the following criteria:',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'mobile.required' => 'The mobile number is required.',
            'mobile.max' => 'The mobile number must not exceed 10 digits.',
            'mobile.regex' => 'The mobile number must be exactly 10 digits.',
            'mobile.unique' => 'The mobile number has already been taken.',
            'profile_pic.image' => 'Profile picture must be an image file.',
            'profile_pic.mimes' => 'Profile picture must be in JPEG, PNG, JPG format.',
            'profile_pic.max' => 'Profile picture size should not exceed 1MB.',
            'profile_pic.min' => 'Profile picture size should not below 100kb.',
        ];
    }
}
