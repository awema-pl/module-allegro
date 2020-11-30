<?php

namespace AwemaPL\Allegro\Sections\Applications\Http\Requests;

use AwemaPL\Allegro\Sections\Options\Models\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplication extends FormRequest
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
            'name' => 'required|string|max:255',
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:65535',
            'sandbox' =>'required|boolean',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => _p('allegro::requests.application.attributes.name', 'Name'),
            'client_id' => _p('allegro::requests.application.attributes.client_id', 'client ID'),
            'client_secret' => _p('allegro::requests.application.attributes.client_secret', 'client secret'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
