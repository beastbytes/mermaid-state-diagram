<?php

use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\StateDiagram\BaseState;
use BeastBytes\Mermaid\StateDiagram\Choice;
use BeastBytes\Mermaid\StateDiagram\Fork;
use BeastBytes\Mermaid\StateDiagram\Group;
use BeastBytes\Mermaid\StateDiagram\Join;
use BeastBytes\Mermaid\StateDiagram\Note;
use BeastBytes\Mermaid\StateDiagram\NotePosition;
use BeastBytes\Mermaid\StateDiagram\State;
use BeastBytes\Mermaid\StateDiagram\Transition;

const STYLE_CLASS = 'style_class';

test('simple state', function () {
    $state = new State('s2');
    expect($state->getId())
        ->toBe('_s2')
    ;
    expect($state->render(''))
        ->toBe('state "s2" as _s2')
    ;
});

test('state with description', function () {
    $state = new State('s2', 'This is a state description');
    expect($state->getId())
        ->toBe('_s2')
    ;
    expect($state->render(''))
        ->toBe('state "This is a state description" as _s2')
    ;
});

test('state with note', function () {
    $state = (new State('s2'))->withNote(new Note('The note', NotePosition::Left));
    expect($state->render(''))
        ->toBe("state \"s2\" as _s2\n"
               . "note left of _s2\n"
               . "  The note\n"
               . "end note"
        )
    ;
});

test('state with style', function () {
    $state = (new State('s2'))->withStyleClass(STYLE_CLASS);
    expect($state->render(''))
        ->toBe('state "s2" as _s2:::' . STYLE_CLASS)
    ;
});

test('special states', function (BaseState $state, string $result) {
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

    $expected = "state \"First\" as _First {\n"
        . "  state \"Second\" as _Second {\n"
        . "    state \"2nd\" as _2nd\n"
        . "    state \"Third\" as _Third {\n"
        . "      state \"3rd\" as _3rd\n"
        . "      [*] --> _3rd\n"
        . "      _3rd --> [*]\n"
        . "    }\n"
        . "    [*] --> _2nd\n"
        . "    _2nd --> _Third\n"
        . "  }\n"
        . "  [*] --> _Second\n"
        . "}"
    ;

    expect($state1->render(''))
        ->toBe($expected)
    ;
});

test('Composite states with direction', function () {
    $state3 = new State('Third');
    $state3i = new State('3rd');
    $state2 = (new State('Second', ''))->withDirection(Direction::BT);
    $state2i = new State('2nd');
    $state1 = (new State('First', ''))->withDirection(Direction::LR);


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

    $expected = "state \"First\" as _First {\n"
        . "  direction LR\n"
        . "  state \"Second\" as _Second {\n"
        . "    direction BT\n"
        . "    state \"2nd\" as _2nd\n"
        . "    state \"Third\" as _Third {\n"
        . "      state \"3rd\" as _3rd\n"
        . "      [*] --> _3rd\n"
        . "      _3rd --> [*]\n"
        . "    }\n"
        . "    [*] --> _2nd\n"
        . "    _2nd --> _Third\n"
        . "  }\n"
        . "  [*] --> _Second\n"
        . "}"
    ;

    expect($state1->render(''))
        ->toBe($expected)
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

    $expected = "state \"Active\" as _Active {\n"
        . "  state \"NumLockOff\" as _NumLockOff\n"
        . "  state \"NumLockOn\" as _NumLockOn\n"
        . "  [*] --> _NumLockOff\n"
        . "  _NumLockOff --> _NumLockOn : EvNumLockPressed\n"
        . "  _NumLockOn --> _NumLockOff : EvNumLockPressed\n"
        . "--\n"
        . "  state \"CapsLockOff\" as _CapsLockOff\n"
        . "  state \"CapsLockOn\" as _CapsLockOn\n"
        . "  [*] --> _CapsLockOff\n"
        . "  _CapsLockOff --> _CapsLockOn : EvCapsLockPressed\n"
        . "  _CapsLockOn --> _CapsLockOff : EvCapsLockPressed\n"
        . "--\n"
        . "  state \"ScrollLockOff\" as _ScrollLockOff\n"
        . "  state \"ScrollLockOn\" as _ScrollLockOn\n"
        . "  [*] --> _ScrollLockOff\n"
        . "  _ScrollLockOff --> _ScrollLockOn : EvScrollLockPressed\n"
        . "  _ScrollLockOn --> _ScrollLockOff : EvScrollLockPressed\n"
        . "}"
    ;

    expect($active->render(''))
        ->toBe($expected)
    ;
});

dataset('specialStates', [
    [new Choice('id'), 'state _id <<choice>>'],
    [new Fork('id'), 'state _id <<fork>>'],
    [new Join('id'), 'state _id <<join>>'],
]);
