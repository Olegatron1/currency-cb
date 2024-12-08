<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetRateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
	public function rules()
	{
		return [
			'date' => 'required|date',
			'quote' => 'required|string',
			'base' => 'sometimes|string',
		];
	}

	public function messages()
	{
		return [
			'date.required' => 'Дата обязательна для заполнения.',
			'date.date' => 'Дата должна быть в формате YYYY-MM-DD.',
			'quote.required' => 'Код котируемой валюты обязателен.',
			'base.string' => 'Код базовой валюты должен быть строкой.',
		];
	}
}
