<?php

use BeastBytes\Mermaid\StateDiagram\Note;
use BeastBytes\Mermaid\StateDiagram\NotePosition;
use BeastBytes\Mermaid\StateDiagram\State;

test('note', function (NotePosition $position, string $result) {
    expect((new Note('This is the note', $position))->render('', new State('s1')))
        ->toBe($result)
    ;
})
    ->with([
        [NotePosition::left, "note left of s1\n  This is the note\nend note"],
        [NotePosition::right, "note right of s1\n  This is the note\nend note"]
    ])
;