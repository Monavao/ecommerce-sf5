<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Calculator
{

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    private float $tva;

    public function __construct(LoggerInterface $logger, float $tva)
    {
        $this->logger = $logger;
        $this->tva    = $tva;
    }

    /**
     * @param float $prix
     * @return float
     */
    public function calcul(float $prix): float
    {
        $this->logger->info("Nouveau calcul de TVA => " . $prix);
        return $prix * (20/100);
    }
}
