<?php

use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\StateDiagram\BaseState;
use BeastBytes\Mermaid\StateDiagram\Choice;
use BeastBytes\Mermaid\StateDiagram\Fork;
use BeastBytes\Mermaid\StateDiagram\Group;
use BeastBytes\Mermaid\StateDiagram\Join;
use BeastBytes\Mermaid\StateDiagram\State;
use BeastBytes\Mermaid\StateDiagram\StateDiagram;
use BeastBytes\Mermaid\StateDiagram\Transition;

test('State diagram', function () {
    $crash = (new State('Crash'))->withStyleClass('classDef2');
    $moving = (new State('Moving'))->withStyleClass('classDef0');
    $still = new State('Still');

    $expected = "<pre class=\"mermaid\">\n"
        . "---\n"
        . "title: Simple sample\n"
        . "---\n"
        . "stateDiagram-v2\n"
        . "  state &quot;Crash&quot; as _Crash:::classDef2\n"
        . "  state &quot;Moving&quot; as _Moving:::classDef0\n"
        . "  state &quot;Still&quot; as _Still\n"
        . "  [*] --&gt; _Still\n"
        . "  _Still --&gt; [*]\n"
        . "  _Still --&gt; _Moving\n"
        . "  _Moving --&gt; _Still\n"
        . "  _Moving --&gt; _Crash\n"
        . "  _Crash --&gt; [*]\n"
        . "  classDef classDef0 fill:white;\n"
        . "  classDef classDef1 font-style:italic;\n"
        . "  classDef classDef2 fill:#f00,color:white,font-weight:bold,stroke-width:2px,stroke:yellow;\n"
        . '</pre>'
    ;

    expect((new StateDiagram('Simple sample'))
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
        ->toBe($expected)
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

    $expected = "<pre class=\"mermaid\">\n"
        . "stateDiagram-v2\n"
        . "  state &quot;First&quot; as _First {\n"
        . "    state &quot;Second&quot; as _Second {\n"
        . "      state &quot;2nd&quot; as _2nd\n"
        . "      state &quot;Third&quot; as _Third {\n"
        . "        state &quot;3rd&quot; as _3rd\n"
        . "        [*] --&gt; _3rd\n"
        . "        _3rd --&gt; [*]\n"
        . "      }\n"
        . "      [*] --&gt; _2nd\n"
        . "      _2nd --&gt; _Third\n"
        . "    }\n"
        . "    [*] --&gt; _Second\n"
        . "  }\n"
        . "  [*] --&gt; _First\n"
        . '</pre>'
    ;

    expect((new StateDiagram())
        ->withState($state1)
        ->withTransition(new Transition(to: $state1))
        ->render()
    )
        ->toBe($expected)
    ;
});

test('Composite states with direction', function () {
    $state3 = new State('Third');
    $state3i = new State('3rd');
    $state2 = (new State('Second'))->withDirection(Direction::BT);
    $state2i = new State('2nd');
    $state1 = (new State('First'))->withDirection(Direction::LR);


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

    $expected = "<pre class=\"mermaid\">\n"
        . "stateDiagram-v2\n"
        . "  state &quot;First&quot; as _First {\n"
        . "    direction LR\n"
        . "    state &quot;Second&quot; as _Second {\n"
        . "      direction BT\n"
        . "      state &quot;2nd&quot; as _2nd\n"
        . "      state &quot;Third&quot; as _Third {\n"
        . "        state &quot;3rd&quot; as _3rd\n"
        . "        [*] --&gt; _3rd\n"
        . "        _3rd --&gt; [*]\n"
        . "      }\n"
        . "      [*] --&gt; _2nd\n"
        . "      _2nd --&gt; _Third\n"
        . "    }\n"
        . "    [*] --&gt; _Second\n"
        . "  }\n"
        . "  [*] --&gt; _First\n"
        . '</pre>'
    ;

    expect((new StateDiagram())
        ->withState($state1)
        ->withTransition(new Transition(to: $state1))
        ->render()
    )
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
        )
    ;

    $expected = "<pre class=\"mermaid\">\n"
        . "stateDiagram-v2\n"
        . "  state &quot;Active&quot; as _Active {\n"
        . "    state &quot;NumLockOff&quot; as _NumLockOff\n"
        . "    state &quot;NumLockOn&quot; as _NumLockOn\n"
        . "    [*] --&gt; _NumLockOff\n"
        . "    _NumLockOff --&gt; _NumLockOn : EvNumLockPressed\n"
        . "    _NumLockOn --&gt; _NumLockOff : EvNumLockPressed\n"
        . "  --\n"
        . "    state &quot;CapsLockOff&quot; as _CapsLockOff\n"
        . "    state &quot;CapsLockOn&quot; as _CapsLockOn\n"
        . "    [*] --&gt; _CapsLockOff\n"
        . "    _CapsLockOff --&gt; _CapsLockOn : EvCapsLockPressed\n"
        . "    _CapsLockOn --&gt; _CapsLockOff : EvCapsLockPressed\n"
        . "  --\n"
        . "    state &quot;ScrollLockOff&quot; as _ScrollLockOff\n"
        . "    state &quot;ScrollLockOn&quot; as _ScrollLockOn\n"
        . "    [*] --&gt; _ScrollLockOff\n"
        . "    _ScrollLockOff --&gt; _ScrollLockOn : EvScrollLockPressed\n"
        . "    _ScrollLockOn --&gt; _ScrollLockOff : EvScrollLockPressed\n"
        . "  }\n"
        . "  [*] --&gt; _Active\n"
        . '</pre>'
    ;

    expect((new StateDiagram())
        ->withState($active)
        ->withTransition(new Transition(to: $active))
        ->render()
    )
        ->toBe($expected)
    ;
});

dataset('specialStates', [
    [new Choice('id'), 'state _id <<choice>>'],
    [new Fork('id'), 'state _id <<fork>>'],
    [new Join('id'), 'state _id <<join>>'],
]);
