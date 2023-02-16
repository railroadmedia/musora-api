<?php

namespace Railroad\MusoraApi\Requests;


class AddItemToPlaylistRequest extends FormRequest
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
            'playlist_id' => 'required',
            'content_id' => 'required',
            'brand' => 'required'
        ];
    }
}