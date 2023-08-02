<?php

namespace App\Http\Requests\Tenant\SmartSchedule;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        switch (last(request()->segments())) {
			case "add-new-note":
				return $this->addNewNote();
			default:
				return $this->deleteNotes();
		}
    }

    protected function addNewNote()
    {
        return [
            "date" => "required",
            "time" => "required",
            "notes" => "string|required"
        ];
    }

    protected function deleteNotes()
    {
        return [
            "note_id" => "required|exists:notes,uuid"
        ];
    }
}
