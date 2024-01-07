<?php

use BeastBytes\Mermaid\StateDiagram\State;
use BeastBytes\Mermaid\StateDiagram\Transition;

test('simple transition', function () {
    $state1 = new State('s1');
    $state2 = new State('s2');

    $transition = new Transition($state1, $state2);

    expect($transition->render(''))
        ->toBe('_s1 --> _s2')
    ;
});

test('transition with label', function () {
    $state1 = new State('s1');
    $state2 = new State('s2');

    $transition = new Transition($state1, $state2, 'Transition label');
    expect($transition->render(''))
        ->toBe('_s1 --> _s2 : Transition label')
    ;
});

test('transition to/from terminus', function () {
    $state1 = new State('s1');

    $transition = new Transition(null, $state1);
    expect($transition->render(''))
        ->toBe('[*] --> _s1')
    ;

    $transition = new Transition($state1, null);
    expect($transition->render(''))
        ->toBe('_s1 --> [*]')
    ;
});
