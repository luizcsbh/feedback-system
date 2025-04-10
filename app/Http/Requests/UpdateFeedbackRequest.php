<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeedbackRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', $this->feedback);
    }

    public function rules()
    {
        return [
            'strengths' => 'sometimes|string|max:1000',
            'improvements' => 'sometimes|string|max:1000',
            'general_impression' => 'sometimes|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'strengths.sometimes' => 'O campo "Pontos Fortes" é obrigatório.',
            'improvements.sometimes' => 'O campo "Pontos a Melhorar" é obrigatório.',
            'general_impression.sometimes' => 'O campo "Impressão Geral" é obrigatório.',
        ];
    }
}