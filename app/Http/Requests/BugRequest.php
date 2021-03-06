<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\JsonResponseTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class BugRequest extends FormRequest
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
            'Member' => ['GET', 'POST', 'PATCH'],
            'Client' => ['GET', 'POST'],
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
                    'project_id' => ['integer'],
                    'milestone_id' => ['integer'],
                    'created_by' => ['integer'],
                    'assigned_to' => ['integer'],
                    'status_id' => ['string', 'max:16'],
                    'title' => ['string', 'max:255'],
                    'desc' => ['string', 'max:255'],
                ];
            case 'POST':
                return [
                    'project_id' => ['required', 'integer', 'exists:projects,id'],
                    'milestone_id' => ['integer', 'exists:milestones,id', 'nullable'],
                    'assigned_to' => ['integer', 'exists:users,id', 'nullable'],
                    'status_id' => ['integer', 'exists:statuses,id', 'nullable'],
                    'priority_id' => ['integer', 'exists:priorities,id', 'nullable'],
                    'difficulty_id' => ['integer', 'exists:difficulties,id', 'nullable'],
                    'title' => ['required', 'string', 'max:255'],
                    'desc' => ['required', 'string', 'max:255'],
                    'solution_desc' => ['string', 'max:255', 'nullable'],
                    'url' => ['URL', 'max:255', 'nullable'],
                    'device_type' => ['string', 'max:32', 'nullable'],
                    'device_os' => ['string', 'max:32', 'nullable'],
                    'browser_info' => ['string', 'max:255', 'nullable'],
                    'tags' => ['json', 'max:255', 'nullable'],
                    'end_date' => ['date', 'nullable'],
                ];
            case 'PATCH':
                return [
                    'project_id' => ['integer', 'exists:projects,id'],
                    'milestone_id' => ['integer', 'exists:milestones,id', 'nullable'],
                    'assigned_to' => ['integer', 'exists:users,id', 'nullable'],
                    'status_id' => ['integer', 'exists:statuses,id', 'nullable'],
                    'priority_id' => ['integer', 'exists:priorities,id', 'nullable'],
                    'difficulty_id' => ['integer', 'exists:difficulties,id', 'nullable'],
                    'title' => ['string', 'max:255'],
                    'desc' => ['string', 'max:255'],
                    'solution_desc' => ['string', 'max:255', 'nullable'],
                    'url' => ['URL', 'max:255', 'nullable'],
                    'device_type' => ['string', 'max:32', 'nullable'],
                    'device_os' => ['string', 'max:32', 'nullable'],
                    'browser_info' => ['string', 'max:255', 'nullable'],
                    'tags' => ['json', 'max:255', 'nullable'],
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
