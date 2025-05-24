<?php

enum ContactReplyStatus: int
{
    case Unread = 0;
    case Answered = 1;
    case Resolved = 2;

    public function asString(): string
    {
        return match ($this->value) {
            0 => 'Unread',
            1 => 'Answered',
            2 => 'Resolved',
            default => '',
        };
    }
}
