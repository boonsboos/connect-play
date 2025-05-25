<?php

enum ContactReplyStatus: int
{
    case Unread = 0;
    case Answered = 1;
    case Resolved = 2;

    public function asString(): string
    {
        return match ($this->value) {
            0 => 'Ongelezen',
            1 => 'Beantwoord',
            2 => 'Opgelost',
            default => '',
        };
    }
}
