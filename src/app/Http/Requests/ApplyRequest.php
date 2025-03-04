<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyRequest extends FormRequest
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
            'note' => 'required',
            'punchIn'  => ['required', 'date_format:H:i:s'],
            'punchOut' => ['nullable', 'date_format:H:i:s', 'after:punchIn'],
            'breakStart' => ['nullable', 'date_format:H:i:s', 'after_or_equal:punchIn', 'before:punchOut'],
            'breakEnd' => ['nullable', 'date_format:H:i:s', 'after:breakStart', 'before_or_equal:punchOut'],
        ];
    }

    public function messages()
    {
        return [
            'note.required' => '備考は必ず記入してください。',
            'punchOut.after' => '退勤時間は出勤時間より後である必要があります。',
            'breakStart.after_or_equal' => '休憩開始時間は出勤時間より後である必要があります。',
            'breakStart.before' => '休憩開始時間は退勤時間より前である必要があります。',
            'breakEnd.after' => '休憩終了時間は休憩開始時間より後である必要があります。',
            'breakEnd.before_or_equal' => '休憩終了時間は退勤時間より前である必要があります。',
        ];
        
    }
}
