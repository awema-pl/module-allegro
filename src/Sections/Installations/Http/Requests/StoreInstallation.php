<?php

namespace AwemaPL\Allegro\Sections\Installations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstallation extends FormRequest
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
            'default_client_id' => 'required|string|max:255',
            'default_client_secret' => 'required|string|max:65535',
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
            'default_client_id' => _p('allegro::requests.installation.attributes.default_client_id', 'Default client ID'),
            'default_client_secret' => _p('allegro::requests.installation.attributes.default_client_secret', 'Default client secret'),
        ];
    }
}
