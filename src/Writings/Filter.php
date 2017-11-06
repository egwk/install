<?php

namespace Egwk\Install\Writings;

/**
 * Description of Filter
 *
 * @author Peter
 */
class Filter
    {

    protected $morphy    = null;

    public function __construct(Morphy $morphy)
        {
        $this->morphy    = $morphy;
        }

    public function strip(string $text): string
        {
        return trim(strip_tags(html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        }

    public function normalize(string $text): string
        {
        return trim(strtolower(preg_replace(['/[\W0-9_]+/', '/(\s)\s+/'], [' ', '$1'], $text)));
        }

    public function split(string $text): array
        {
        return array_filter(array_map('trim', explode(' ', $text)));
        }

    public function stick(array $words): string
        {
        return implode(' ', $words);
        }

    public function killStopWords(array $words): array
        {
        return array_filter(array_map(function($word)
                    {
                    $word = trim($word);
                    if (strlen($word) < 2)
                        {
                        return "";
                        }
                    return $word;
                    }, array_diff($words, config('install.stopwords', []))));
        }

    public function lemmatize(array $words): array
        {
        return $this->morphy->lemmatize($words);
        }

    public function sort(array $words): array
        {
        sort($words);
        return array_unique($words);
        }

    public function getMorphy(): WritingsMorphy
        {
        return $this->morphy;
        }

    public function getStopWords(): array
        {
        return config('install.stopwords', []);
        }

    public function flow(string $text, array $flow): string
        {
        foreach ($flow as $transformation)
            {
            if (is_callable([$this, $transformation]))
                {
                $text = $this->{$transformation}($text);
                }
            }
        return $text;
        }

    }
