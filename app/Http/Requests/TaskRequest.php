<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   */
  public function rules(): array
  {
    return [
      'title' => 'required|string|max:50',
      'description' => 'nullable|string|max:255',
    ];
  }

  /**
   * Get custom messages for validator errors.
   */
  public function messages(): array
  {
    return [
      'title.required' => 'The title field is required.',
      'title.max' => 'The title may not be greater than 50 characters.',
      'description.max' => 'The description may not be greater than 255 characters.',
    ];
  }

  /**
   * Handle a failed validation attempt.
   */
  protected function failedValidation(Validator $validator)
  {
    throw new HttpResponseException(
      response()->json([
        'errors' => $validator->errors()
      ], 422)
    );
  }

  /**
   * Configure the validator instance.
   */
  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $data = $this->all();

      // Check if there are any unknown fields
      $allowedFields = ['title', 'description'];
      $unknownFields = array_diff(array_keys($data), $allowedFields);

      if (!empty($unknownFields)) {
        foreach ($unknownFields as $field) {
          $validator->errors()->add($field, "The field '{$field}' is not allowed.");
        }
      }
    });
  }
}
