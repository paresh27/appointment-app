<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookAnAppointmentRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => ['required', 'date', 'after:now'],
            'appointment_end_time' => ['required', 'date', 'after:appointment_start_time'],
        ];
    }
}
