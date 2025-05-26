<?php

enum OrderStatus: string
{
    case Pending = 'PENDING';
    case Paid = 'PAID';
    case Cancelled = 'CANCELLED';
    case Underway = 'UNDERWAY';
    case Delivered = 'DELIVERED';
    case Returned = 'RETURNED';

    public function asString(): string
    {
        return match ($this->value) {
            'PENDING' => 'In afwachting',
            'PAID' => 'Betaald',
            'CANCELLED' => 'Geannuleerd',
            'UNDERWAY' => 'In uitvoering',
            'DELIVERED' => 'Afgeleverd',
            'RETURNED' => 'Geretourneerd',
            default => '',
        };
    }
}
