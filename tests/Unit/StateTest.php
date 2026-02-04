<?php

use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\StateDiagram\State;
use BeastBytes\Mermaid\StateDiagram\Choice;
use BeastBytes\Mermaid\StateDiagram\Fork;
use BeastBytes\Mermaid\StateDiagram\Group;
use BeastBytes\Mermaid\StateDiagram\Join;
use BeastBytes\Mermaid\StateDiagram\Note;
use BeastBytes\Mermaid\StateDiagram\NotePosition;
use BeastBytes\Mermaid\StateDiagram\StateInterface;
use BeastBytes\Mermaid\StateDiagram\Transition;

defined('COMMENT') or define('COMMENT', 'Comment');
defined('STYLE_CLASS') or define('STYLE_CLASS', 'style_class');

test('simple state', function () {
    $state = new State('s2');
    expect($state->getId())
        ->toBe('s2')
        ->and($state->render(''))
        ->toBe('state "s2" as s2')
    ;
});

test('state with comment', function () {
    expect((new State('s2'))->withComment(COMMENT)->render(''))
        ->toBe(<<<EXPECTED
%% Comment
state "s2" as s2
EXPECTED
        )
    ;
});

test('state with description', function () {
    $state = new State('s2', 'This is a state description');
    expect($state->getId())
        ->toBe('s2')
        ->and($state->render(''))
        ->toBe('state "This is a state description" as s2')
    ;
});

test('state with note', function () {
    $state = (new State('s2'))->withNote(new Note('The note', NotePosition::left));
    expect($state->render(''))
        ->toBe(<<<EXPECTED
state "s2" as s2
note left of s2
  The note
end note
EXPECTED
        )
    ;
});

test('state with style', function () {
    $state = (new State('s2'))->withStyleClass(STYLE_CLASS);
    expect($state->render(''))
        ->toBe('state "s2" as s2:::' . STYLE_CLASS)
    ;
});

test('special states', function (StateInterface $state, string $result) {
    expect($state->render(''))
        ->toBe($result)
    ;
})
    ->with('specialStates')
;

test('Composite states', function () {
    $state3 = new State('Third');
    $state3i = new State('3rd');
    $state2 = new State('Second');
    $state2i = new State('2nd');
    $state1 = new State('First');

    $state3 = $state3
        ->withState($state3i)
        ->withTransition(
            new Transition(to: $state3i),
            new Transition(from: $state3i)
        )
    ;

    $state2 = $state2
        ->withState($state2i, $state3)
        ->withTransition(
            new Transition(to: $state2i),
            new Transition($state2i, $state3)
        )
    ;

    $state1 = $state1
        ->withState($state2)
        ->withTransition(new Transition(to: $state2))
    ;

    expect($state1->render(''))
        ->toBe(<<<EXPECTED
state "First" as First {
  state "Second" as Second {
    state "2nd" as 2nd
    state "Third" as Third {
      state "3rd" as 3rd
      [*] --> 3rd
      3rd --> [*]
    }
    [*] --> 2nd
    2nd --> Third
  }
  [*] --> Second
}
EXPECTED
        )
    ;
});

test('Composite states with direction', function () {
    $state3 = new State('Third');
    $state3i = new State('3rd');
    $state2 = (new State('Second'))->withDirection(Direction::bottomTop);
    $state2i = new State('2nd');
    $state1 = (new State('First'))->withDirection(Direction::leftRight);

    $state3 = $state3
        ->withState($state3i)
        ->withTransition(
            new Transition(to: $state3i),
            new Transition(from: $state3i)
        )
    ;

    $state2 = $state2
        ->withState($state2i, $state3)
        ->withTransition(
            new Transition(to: $state2i),
            new Transition($state2i, $state3)
        )
    ;

    $state1 = $state1
        ->withState($state2)
        ->withTransition(new Transition(to: $state2))
    ;

    expect($state1->render(''))
        ->toBe(<<<EXPECTED
state "First" as First {
  direction LR
  state "Second" as Second {
    direction BT
    state "2nd" as 2nd
    state "Third" as Third {
      state "3rd" as 3rd
      [*] --> 3rd
      3rd --> [*]
    }
    [*] --> 2nd
    2nd --> Third
  }
  [*] --> Second
}
EXPECTED
        )
    ;
});

test('Concurrent states', function () {
    $active = new State('Active');
    $numLockOff = new State('NumLockOff');
    $numLockOn = new State('NumLockOn');
    $capsLockOff = new State('CapsLockOff');
    $capsLockOn = new State('CapsLockOn');
    $scrollLockOff = new State('ScrollLockOff');
    $scrollLockOn = new State('ScrollLockOn');

    $active = $active
        ->withGroup(
            (new Group())
                ->withState($numLockOff, $numLockOn)
                ->withTransition(
                    new Transition(null, $numLockOff),
                    new Transition($numLockOff, $numLockOn, 'EvNumLockPressed'),
                    new Transition($numLockOn, $numLockOff, 'EvNumLockPressed')
                )
            ,
            (new Group())
                ->withState($capsLockOff, $capsLockOn)
                ->withTransition(
                    new Transition(null, $capsLockOff),
                    new Transition($capsLockOff, $capsLockOn, 'EvCapsLockPressed'),
                    new Transition($capsLockOn, $capsLockOff, 'EvCapsLockPressed')
                )
            ,
            (new Group())
                ->withState($scrollLockOff, $scrollLockOn)
                ->withTransition(
                    new Transition(null, $scrollLockOff),
                    new Transition($scrollLockOff, $scrollLockOn, 'EvScrollLockPressed'),
                    new Transition($scrollLockOn, $scrollLockOff, 'EvScrollLockPressed')
                )
            ,
        )
    ;

    expect($active->render(''))
        ->toBe(<<<EXPECTED
state "Active" as Active {
  state "NumLockOff" as NumLockOff
  state "NumLockOn" as NumLockOn
  [*] --> NumLockOff
  NumLockOff --> NumLockOn : EvNumLockPressed
  NumLockOn --> NumLockOff : EvNumLockPressed
  --
  state "CapsLockOff" as CapsLockOff
  state "CapsLockOn" as CapsLockOn
  [*] --> CapsLockOff
  CapsLockOff --> CapsLockOn : EvCapsLockPressed
  CapsLockOn --> CapsLockOff : EvCapsLockPressed
  --
  state "ScrollLockOff" as ScrollLockOff
  state "ScrollLockOn" as ScrollLockOn
  [*] --> ScrollLockOff
  ScrollLockOff --> ScrollLockOn : EvScrollLockPressed
  ScrollLockOn --> ScrollLockOff : EvScrollLockPressed
}
EXPECTED
        )
    ;
});

dataset('specialStates', [
    [new Choice('id'), 'state id <<choice>>'],
    [new Fork('id'), 'state id <<fork>>'],
    [new Join('id'), 'state id <<join>>'],
]);