<?php

namespace Railroad\MusoraApi\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubmitStudentFocusFormRequest extends FormRequest
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
            'experience' => 'required',
            'improvement' => 'required',
            'weakness' => 'required',
            'instructor_focus' => 'required',
            'goal' => 'required',
        ];
    }

    /** Get the failed validation response in json format
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'title' => 'Submission Failed',
            'message' => implode(
                ',',
                $validator->getMessageBag()
                    ->all()
            ),
        ], 422
        ));
    }
}