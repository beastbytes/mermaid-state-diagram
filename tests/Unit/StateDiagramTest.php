<?php

use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\StateDiagram\State;
use BeastBytes\Mermaid\StateDiagram\Choice;
use BeastBytes\Mermaid\StateDiagram\Fork;
use BeastBytes\Mermaid\StateDiagram\Group;
use BeastBytes\Mermaid\StateDiagram\Join;
use BeastBytes\Mermaid\StateDiagram\StateDiagram;
use BeastBytes\Mermaid\StateDiagram\Transition;

defined('COMMENT') or define('COMMENT', 'Comment');

test('State diagram', function () {
    $crash = (new State('Crash'))->withStyleClass('classDef2');
    $moving = (new State('Moving'))->withStyleClass('classDef0');
    $still = new State('Still');

    expect(Mermaid::create(StateDiagram::class, ['title' => 'Simple sample'])
        ->withComment(COMMENT)
        ->withState($crash, $moving, $still)
        ->withTransition(
            new Transition(to: $still),
            new Transition(from: $still),
            new Transition(from: $still, to: $moving),
            new Transition(from: $moving, to: $still),
            new Transition(from: $moving, to: $crash),
            new Transition(from: $crash),
        )
        ->withClassDef([
            'classDef0' => 'fill:white',
            'classDef1' => ['font-style' => 'italic']
        ])
        ->addClassDef(['classDef2' => [
            'fill' => '#f00',
            'color' => 'white',
            'font-weight' => 'bold',
            'stroke-width' => '2px',
            'stroke' => 'yellow'
        ]])
        ->render()
    )
        ->toBe(<<<EXPECTED
<pre class="mermaid">
---
title:Simple sample
---
%% Comment
stateDiagram-v2
  state "Crash" as Crash:::classDef2
  state "Moving" as Moving:::classDef0
  state "Still" as Still
  [*] --> Still
  Still --> [*]
  Still --> Moving
  Moving --> Still
  Moving --> Crash
  Crash --> [*]
  classDef classDef0 fill:white;
  classDef classDef1 font-style:italic;
  classDef classDef2 fill:#f00,color:white,font-weight:bold,stroke-width:2px,stroke:yellow;
</pre>
EXPECTED
        )
    ;
});

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



    expect(Mermaid::create(StateDiagram::class)
        ->withState($state1)
        ->withTransition(new Transition(to: $state1))
        ->render()
    )
        ->toBe(<<<EXPECTED
<pre class="mermaid">
stateDiagram-v2
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
  [*] --> First
</pre>
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

    expect(Mermaid::create(StateDiagram::class)
        ->withState($state1)
        ->withTransition(new Transition(to: $state1))
        ->render()
    )
        ->toBe(<<<EXPECTED
<pre class="mermaid">
stateDiagram-v2
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
  [*] --> First
</pre>
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
        )
    ;

    expect(Mermaid::create(StateDiagram::class)
        ->withState($active)
        ->withTransition(new Transition(to: $active))
        ->render()
    )
        ->toBe(<<<EXPECTED
<pre class="mermaid">
stateDiagram-v2
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
  [*] --> Active
</pre>
EXPECTED
        )
    ;
});

test('Example', function () {
    $fork = new Fork('fork_state');
    $join = new Join('join_state');
    $choice = new Choice('if_state');
    $state1 = new State('State1');
    $state2 = new State('State2');
    $state3 = new State('State3');
    $state4 = new State('State4');
    $isPositive = new State('IsPositive', 'Is Positive');
    $false = new State('False');
    $true = new State('True');
    $first = (new State('First'))
        ->withState($fir = new State('fir'))
        ->withTransition(
            new Transition(null, $fir),
            new Transition($fir)
        )
    ;
    $second = (new State('Second'))
        ->withState($sec = new State('sec'))
        ->withTransition(
            new Transition(null, $sec),
            new Transition($sec)
        )
    ;
    $third = (new State('Third'))
        ->withState($thi = new State('thi'))
        ->withTransition(
            new Transition(null, $thi),
            new Transition($thi)
        )
    ;
    $active = (new State('Active'))
        ->withGroup(
            (new Group())
                ->withState(
                    $capsLockOff = new State('CapsLockOff'),
                    $capsLockOn = new State('CapsLockOn')
                )
                ->withTransition(
                    new Transition(null, $capsLockOff),
                    new Transition($capsLockOff, $capsLockOn, 'EvCapsLockPressed'),
                    new Transition($capsLockOn, $capsLockOff, 'EvCapsLockPressed')
                )
            ,
            (new Group())
                ->withState(
                    $numLockOff = new State('NumLockOff'),
                    $numLockOn = new State('NumLockOn')
                )
                ->withTransition(
                    new Transition(null, $numLockOff),
                    new Transition($numLockOff, $numLockOn, 'EvNumLockPressed'),
                    new Transition($numLockOn, $numLockOff, 'EvNumLockPressed')
                )
            ,
            (new Group())
                ->withState(
                    $scrollLockOff = new State('ScrollLockOff'),
                    $scrollLockOn = new State('ScrollLockOn')
                )
                ->withTransition(
                    new Transition(null, $scrollLockOff),
                    new Transition($scrollLockOff, $scrollLockOn, 'EvScrollLockPressed'),
                    new Transition($scrollLockOn, $scrollLockOff, 'EvScrollLockPressed')
                )
            ,
        )
    ;

    expect(Mermaid::create(StateDiagram::class)
        ->withDirection(Direction::leftRight)
        ->withState(
            $fork,
            $join,
            $choice,
            $state1,
            $state2,
            $state3,
            $state4,
            $false,
            $true,
            $active,
            $first,
            $second,
            $third
        )
        ->withTransition(
            new Transition(null, $fork),
            new Transition($fork, $state1),
            new Transition($fork, $state2),
            new Transition($state1, $join),
            new Transition($state2, $join),
            new Transition($join, $state4),
            new Transition($state4, $isPositive),
            new Transition($isPositive, $choice),
            new Transition($choice, $false, 'if n < 0'),
            new Transition($choice, $true, 'if n >= 0'),
            new Transition($false, $active),
            new Transition($true, $first),
            new Transition($first, $second),
            new Transition($first, $third),
        )
        ->render()
    )
        ->toBe(<<<EXPECTED
<pre class="mermaid">
stateDiagram-v2
  direction LR
  state fork_state <<fork>>
  state join_state <<join>>
  state if_state <<choice>>
  state "State1" as State1
  state "State2" as State2
  state "State3" as State3
  state "State4" as State4
  state "False" as False
  state "True" as True
  state "Active" as Active {
    state "CapsLockOff" as CapsLockOff
    state "CapsLockOn" as CapsLockOn
    [*] --> CapsLockOff
    CapsLockOff --> CapsLockOn : EvCapsLockPressed
    CapsLockOn --> CapsLockOff : EvCapsLockPressed
    --
    state "NumLockOff" as NumLockOff
    state "NumLockOn" as NumLockOn
    [*] --> NumLockOff
    NumLockOff --> NumLockOn : EvNumLockPressed
    NumLockOn --> NumLockOff : EvNumLockPressed
    --
    state "ScrollLockOff" as ScrollLockOff
    state "ScrollLockOn" as ScrollLockOn
    [*] --> ScrollLockOff
    ScrollLockOff --> ScrollLockOn : EvScrollLockPressed
    ScrollLockOn --> ScrollLockOff : EvScrollLockPressed
  }
  state "First" as First {
    state "fir" as fir
    [*] --> fir
    fir --> [*]
  }
  state "Second" as Second {
    state "sec" as sec
    [*] --> sec
    sec --> [*]
  }
  state "Third" as Third {
    state "thi" as thi
    [*] --> thi
    thi --> [*]
  }
  [*] --> fork_state
  fork_state --> State1
  fork_state --> State2
  State1 --> join_state
  State2 --> join_state
  join_state --> State4
  State4 --> IsPositive
  IsPositive --> if_state
  if_state --> False : if n < 0
  if_state --> True : if n >= 0
  False --> Active
  True --> First
  First --> Second
  First --> Third
</pre>
EXPECTED
        )
    ;
});

dataset('specialStates', [
    [new Choice('id'), 'state id <<choice>>'],
    [new Fork('id'), 'state id <<fork>>'],
    [new Join('id'), 'state id <<join>>'],
]);