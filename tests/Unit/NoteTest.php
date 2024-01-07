<?php

use BeastBytes\Mermaid\StateDiagram\Note;
use BeastBytes\Mermaid\StateDiagram\NotePosition;
use BeastBytes\Mermaid\StateDiagram\State;

test('note', function (NotePosition $position, string $result) {
    $note = new Note('This is the note', $position);

    expect($note->render('', new State('s1')))
        ->toBe($result)
    ;
})
    ->with([
        [NotePosition::Left, "note left of _s1\n  This is the note\nend note"],
        [NotePosition::Right, "note right of _s1\n  This is the note\nend note"]
    ])
;
