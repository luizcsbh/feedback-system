<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'strengths' => 'required|string|max:1000',
            'improvements' => 'required|string|max:1000',
            'general_impression' => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'strengths.required' => 'O campo "Pontos Fortes" é obrigatório.',
            'improvements.required' => 'O campo "Pontos a Melhorar" é obrigatório.',
            'general_impression.required' => 'O campo "Impressão Geral" é obrigatório.',
        ];
    }
}