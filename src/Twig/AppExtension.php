<?php

namespace App\Twig;

use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Custom Twig extension for formatting prices.
 *
 * @extends TwigExtension
 */
class AppExtension extends AbstractExtension
{
    /**
     * Get the Twig filters provided by this extension.
     *
     * @return TwigFilter[] An array of TwigFilter instances
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    /**
     * Format the given number as a price.
     *
     * @param mixed  $number       The number to format
     * @param int    $decimals     The number of decimal places
     * @param string $decPoint     The character used as the decimal point
     * @param string $thousandsSep The thousands separator character
     *
     * @return string The formatted price
     */
    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ','): string
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        return '$' . $price;
    }

    /**
     * Get the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return 'price';
    }
}