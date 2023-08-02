<?php

namespace App\Http\Controllers\Tenant\SmartSchedule;

use App\Models\Tenant\{Note, User};
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SmartSchedule\NoteRequest;

class NoteController extends Controller
{
    public function addNewNote(NoteRequest $request)
    {
        Note::create([
            "edited_by" => auth()->user()->username,
            "date"  => dateFormat($request->date),
            "time" => timeFormat($request->time),
            "notes" => $request->notes,
        ]);

        return json_response(200, __("Tenant.note_save_success"));
    }

    public function getNotes()
    {
        $notes = Note::get();
        return json_response(200, __("Tenant.note_get_list"), $notes);
    }

    public function deleteNotes(NoteRequest $request)
    {
        $note = Note::findByUUIDOrFail($request->note_id);
        $note->delete();
        return json_response(200, __("Tenant.note_delete"));
    }
}
