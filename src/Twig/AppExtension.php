<?php

namespace App\Twig;

class AppExtension extends \Twig\Extension\AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new \Twig\TwigFilter('parse_description', [$this, 'parseDescription'], ['is_safe' => ['html']]),
        ];
    }

    public function parseDescription(string $content): string
    {
        // [portee|courte-moyenne|longue]
        $content = preg_replace_callback('/\[portee\|([^\|]+)\|([^\]]+)\]/', function ($matches) {
            $greenParts = explode('-', $matches[1]);
            $redParts = explode('-', $matches[2]);
            
            $html = 'portée ';
            foreach ($greenParts as $part) {
                $html .= '<span class="text-green-500">' . htmlspecialchars($part) . '</span> ';
            }
            foreach ($redParts as $part) {
                $html .= '<span class="text-red-500">' . htmlspecialchars($part) . '</span> ';
            }
            return trim($html);
        }, $content);

        // [token|poison]
        $content = preg_replace_callback('/\[token\|([^\]]+)\]/', function ($matches) {
            $token = htmlspecialchars($matches[1]);
            return '<i class="fas fa-circle text-accent" title="' . $token . '"></i>';
        }, $content);

        // [de|x]
        $content = preg_replace_callback('/\[de\|(\d+)\]/', function ($matches) {
            $count = (int)$matches[1];
            $html = '';
            for ($i = 0; $i < $count; $i++) {
                $html .= '<i class="fas fa-dice-six"></i> ';
            }
            return trim($html);
        }, $content);

        // [reussite]
        $content = str_replace('[reussite]', '<i class="fas fa-dice-six text-green-500"></i>', $content);

        return $content;
    }
}
