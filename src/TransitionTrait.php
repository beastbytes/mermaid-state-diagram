<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

trait TransitionTrait
{
    /** @var Transition[] $transitions */
    private array $transitions = [];

    public function addTransition(Transition ...$transition): self
    {
        $new = clone $this;
        $new->transitions = array_merge($new->transitions, $transition);
        return $new;
    }

    public function withTransition(Transition ...$transition): self
    {
        $new = clone $this;
        $new->transitions = $transition;
        return $new;
    }
}