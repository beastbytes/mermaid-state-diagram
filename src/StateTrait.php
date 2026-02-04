<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

trait StateTrait
{
    /** @var list<StateInterface> $states */
    private array $states = [];

    public function addState(StateInterface ...$state): self
    {
        $new = clone $this;
        $new->states = array_merge($new->states, $state);
        return $new;
    }

    public function withState(StateInterface ...$state): self
    {
        $new = clone $this;
        $new->states = $state;
        return $new;
    }
}