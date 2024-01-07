<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

trait StateTrait
{
    /** @var BaseState[] $states */
    private array $states = [];

    public function addState( BaseState ...$state): self
    {
        $new = clone $this;
        $new->states = array_merge($new->states, $state);
        return $new;
    }

    public function withState(BaseState ...$state): self
    {
        $new = clone $this;
        $new->states = $state;
        return $new;
    }
}
