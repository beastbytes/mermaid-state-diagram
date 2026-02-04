Usage
=====

StateDiagram allows the creation of state diagrams.

StateDiagram Objects
--------------------

States
******

.. note::

    StateDiagram documentation differentiates between *state* and *State*

    *state* is an implementation of :php:interface:`StateInterface`
    and may be the source and/or destination of a transition.

    *State* is a particular implementation of :php:interface:`StateInterface` that represents a system state
    and may contain States, Choices, Forks, Joins, and Groups.

The types of states are:

* State - State represent a particular state of a system.
    A State may contain States, Choices, Forks, and Joins;
    such a State is a `Composite States <https://mermaid.js.org/syntax/stateDiagram.html#composite-states>`__.
    Composite states can be nested to any depth. States may also contain Groups and/or have a Note attached.

* Choice - Choice states represent a choice, allowing the transition path to be dependent on a value.

* Fork - Fork states represent a fork, allowing two or more paths to be followed.

* Join - Join states represent a join of multiple paths into a single path.

Groups
******

Groups may be added to States. Groups contain nodes and transitions.
Groups within a State indicate `concurrency <https://mermaid.js.org/syntax/stateDiagram.html#concurrency>`__.

Notes
*****

Notes can be added to States. A Note provides additional information about the State it is added to.

Transitions
***********

Transitions are paths/edges from one state to another. Transitions can be labelled to describe the transition.

Example
-------

PHP
***

.. code-block:: php

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
    $first = (new State('First))
        ->withState($fir = new State('fir'))
        ->withTransition(null, $fir)
        ->withTransition($fir)
    ;
    $second = (new State('Second'))
        ->withState($sec = new State('sec'))
        ->withTransition(null, $sec)
        ->withTransition($sec)
    ;
    $third = (new State('Third'))
        ->withState($thi = new State('thi'))
        ->withTransition(null, $thi)
        ->withTransition($thi)
    ;
    $active = (new State('Active))
        ->withGroup(
            (new Group())
                ->withState(
                    $capsLockOff = new State(CapsLockOff),
                    $capsLockOn = new State(CapsLockOn)
                )
                ->withTransition(
                    new Transition(null, $capsLockOff),
                    new Transition($capsLockOff, $capsLockOn),
                    new Transition($capsLockOn, $capsLockOff)
                )
            ,
            (new Group())
                ->withState(
                    $numLockOff = State(NumLockOff),
                    $numLockOn = new State(NumLockOn)
                )
                ->withTransition(
                    new Transition(null, $numLockOff),
                    new Transition($numLockOff, $numLockOn),
                    new Transition($numLockOn, $numLockOff)
                )
            ,
            (new Group())
                ->withState(
                    $scrollLockOff = new State(ScrollLockOff),
                    $scrollLockOn = new State(ScrollLockOn)
                )
                ->withTransition(
                    new Transition(null, $scrollLockOff),
                    new Transition($scrollLockOff, $scrollLockOn),
                    new Transition($scrollLockOn, $scrollLockOff)
                )
            ,
        )
    ;

    echo Mermaid::create(StateDiagram::class)
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
            $active
        )
        withTransition(
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
            new Transition($tree, $first),
            new Transition($first, $second),
            new Transition($first, $third),
        )
        ->render()
    ;

Generated Mermaid
*****************

.. code-block:: html

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

Mermaid Diagram
***************

.. mermaid::

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
