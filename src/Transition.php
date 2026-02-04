<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

final class Transition
{
    private const string LABEL = ' : %s';
    private const string START_END = '[*]';
    private const string TRANSITION = '%s --> %s%s';

    /**
     * @param ?StateInterface $from The state to transition from; default - start state
     * @param ?StateInterface $to The state to transition to; default - end state
     * @param ?string $label Label for the transition; default - no label
     */
    public function __construct(
        private readonly ?StateInterface $from = null,
        private readonly ?StateInterface $to = null,
        private readonly ?string $label = null
    )
    {
    }

    /** @internal */
    public function render(string $indentation): string
    {
        return $indentation .sprintf(
            self::TRANSITION,
                $this->from instanceof StateInterface ? $this->from->getId() : self::START_END,
            $this->to instanceof StateInterface ? $this->to->getId() : self::START_END,
            is_string($this->label) ? sprintf(self::LABEL, $this->label) : ''
        );
    }
}