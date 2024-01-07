<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

final class Transition
{
    private const START_END = '[*]';

    /**
     * @param BaseState|null $from The state the transition is from, or NULL for a start state
     * @param BaseState|null $to The state the transition is to, or NULL for an end state
     * @param string $label Label for the transition
     */
    public function __construct(
        private readonly ?BaseState $from = null,
        private readonly ?BaseState $to = null,
        private readonly string $label = ''
    )
    {
    }

    /** @internal */
    public function render(string $indentation): string
    {
        return $indentation
            . ($this->from === null ? self::START_END : $this->from->getId())
            . ' --> '
            . ($this->to === null ? self::START_END : $this->to->getId())
            . (($this->label === '' ? '' : ' : ' . $this->label));
    }
}
