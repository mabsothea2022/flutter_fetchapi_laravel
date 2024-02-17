<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userModel =get_class(new User);
        return [
            'user_id'=>'required|exists:{$userModel}.id',
            'name'=>'nullable',
            'is_private'=>'nullable|boolean'
        ];
    }
}
