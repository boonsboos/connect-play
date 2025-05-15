<?php
// Dit is zodat de uitleg van Mario niet verloren gaat
class OrderRepository
{

    // met constructor property promotion hoef je de properties niet apart te declareren bovenaan de klasse
    public function __construct(
        private PDO $db // zo wordt er herbruikbare pdo object gemaakt die je kunt gebruiken in meerdere methods
    ) {}
}
