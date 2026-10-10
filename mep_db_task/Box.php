<?php

use Psr\Log\LoggerInterface;

class Box {
    private LoggerInterface $logger;
    private int $fruitCount = 10; // Sample count

    // Inject the PSR-3 logger via the constructor
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function getCount(): int {
        // Log the call as requested
        $this->logger->info("The box currently contains {$this->fruitCount} pieces of fruits");
        return $this->fruitCount;
    }
}