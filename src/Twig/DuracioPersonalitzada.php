<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DuracioPersonalitzada extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('duracioPersonalitzada', [$this, 'formatDuracio']),
        ];
    }

    public function formatDuracio(float $duracio): string
    {
        $hores = floor($duracio);               // retorna la part entera
        $decimal = round($duracio - $hores, 2); // retorna la part decimal

        $minuts = ($decimal == 0.5) ? '30' : '00';

        return $hores . ':' . $minuts . 'h';
    }
}
