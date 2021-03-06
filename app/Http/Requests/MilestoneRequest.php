<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\JsonResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class MilestoneRequest extends FormRequest
{
    use JsonResponseTrait;
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        $user_role = auth()->user()->role;

        $perms = [
            'Admin' => ['GET', 'POST', 'PATCH', 'DELETE'],
            'Manager' => ['GET', 'POST', 'PATCH', 'DELETE'],
            'Member' => ['GET'],
            'Client' => ['GET'],
        ];

        if (in_array($request->method(), $perms[$user_role])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        switch ($request->method()) {
            case 'GET':
                return [
                    'project_id' => ['integer', 'exists:projects,id'],
                    'title' => ['string', 'max:255'],
                    'desc' => ['string', 'max:255'],
                ];
            case 'POST':
                return [
                    'project_id' => ['required', 'integer', 'exists:projects,id'],
                    'title' => ['required', 'string', 'max:255'],
                    'desc' => ['required', 'string', 'max:255'],
                    'start_date' => ['date', 'nullable'],
                    'end_date' => ['date', 'nullable'],
                ];
            case 'PATCH':
                return [
                    'project_id' => ['integer', 'exists:projects,id'],
                    'title' => ['string', 'max:255'],
                    'desc' => ['string', 'max:255'],
                    'start_date' => ['date', 'nullable'],
                    'end_date' => ['date', 'nullable'],
                ];
            case 'DELETE':
                return [];
        }
    }

    /**
     * Throw exception if validation failes
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationResponse($validator->errors())
        );
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->authResponse()
        );
    }
}
