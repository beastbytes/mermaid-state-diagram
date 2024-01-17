<?php
/**
 * @copyright Copyright © 2024 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Mermaid\StateDiagram;

use BeastBytes\Mermaid\CommentTrait;
use BeastBytes\Mermaid\Direction;
use BeastBytes\Mermaid\DirectionTrait;
use BeastBytes\Mermaid\Mermaid;
use BeastBytes\Mermaid\RenderItemsTrait;
use BeastBytes\Mermaid\StyleClassTrait;

final class State extends BaseState
{
    use CommentTrait;
    use DirectionTrait;
    use RenderItemsTrait;
    use StateTrait;
    use StyleClassTrait;
    use TransitionTrait;

    private array $groups = [];
    private ?Note $note = null;

    public function __construct(
        string $id,
        private string $description = ''
    )
    {
        parent::__construct($id);

        if ($this->description === '') {
            $this->description = $id;
        }
    }

    public function addGroup(Group ...$group): self
    {
        $new = clone $this;
        $new->groups = array_merge($new->groups, $group);
        return $new;
    }

    public function withGroup(Group ...$group): self
    {
        $new = clone $this;
        $new->groups = $group;
        return $new;
    }

    public function withNote(Note $note): self
    {
        $new = clone $this;
        $new->note = $note;
        return $new;
    }

    /** @internal */
    public function render(string $indentation): string
    {
        $output = [];

        $this->renderComment($indentation, $output);
        $output[] = $indentation . 'state "' . $this->description . '" as ' . $this->getId() . $this->getStyleClass();

         if (count($this->states) > 0 || count($this->groups) > 0) {
            $output[0] .= ' {';

             if ($this->direction !== Direction::TB) {
                 $output[] = $indentation . Mermaid::INDENTATION . $this->getDirection();
             }

             if (count($this->states) > 0) {
                 $this->renderItems($this->states, $indentation, $output);
                 $this->renderItems($this->transitions, $indentation, $output);
             }

             if (count($this->groups) > 0) {
                $this->renderItems($this->groups, $indentation, $output);
                $groups = array_pop($output);
                $output[] = substr($groups, 0, strrpos($groups, "\n")); // remove final concurrency operator
             }

             $output[] = $indentation . '}';
         }

        if ($this->note !== null) {
            $output[] = $this
                ->note
                ->render($indentation, $this)
            ;
        }

        return implode("\n", $output);
    }
}
