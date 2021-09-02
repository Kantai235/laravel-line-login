<?php

namespace App\Domains\Chat\Http\Requests\Backend\Reply;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DeleteReplyRequest.
 */
class DeleteReplyRequest extends FormRequest
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
            // ...
        ];
    }
}
