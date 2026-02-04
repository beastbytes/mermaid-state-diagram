<?php

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\Mermaid;

final class Note
{
    public const string NOTE = <<<'NOTE'
%snote %s of %s
%1$s%s%s
%1$send note
NOTE;

    public function __construct(
        private readonly string $note,
        private readonly NotePosition $position
    )
    {
    }

    /** @internal */
    public function render(string $indentation, State $state): string
    {
        return sprintf(
            self::NOTE,
            $indentation,
            $this->position->name,
            $state->getId(),
            Mermaid::INDENTATION,
            $this->note
        );
    }
}