<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\Mermaid;

final class Note
{
    public function __construct(
        private readonly string $note,
        private readonly NotePosition $position
    )
    {
    }

    /** @internal  */
    public function render(string $indentation, BaseState $state): string
    {
        $output = [];
        $output[] = $indentation . 'note ' .  $this->position->value . ' of ' . $state->getId();
        $output[] = $indentation . Mermaid::INDENTATION . $this->note;
        $output[] = $indentation . 'end note';

        return implode("\n", $output);
    }
}
