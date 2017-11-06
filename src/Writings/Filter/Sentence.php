<?php

namespace Egwk\Install\Writings\Filter;

use Egwk\Install\Writings\Filter;

/**
 * Description of WritingsSentenceFilter
 *
 * @author Peter
 */
class Sentence
    {

    const TEMP_SEPARATOR = '@';
    const SEPARATORS     = '?.!';

    protected $filter;

    public function __construct(Filter $filter)
        {
        $this->filter = $filter;
        }

    public function splitIntoSentences(string $text): array
        {
        $sentencesTmp = [];
        preg_match_all('/.*?[' . self::SEPARATORS . ']/s', $this->sentencePreFilters($text), $sentencesTmp);
        $sentences    = array_shift($sentencesTmp);
        return $this->sentencePostFilters($sentences);
        }

    public function filterSentences(array $sentences, $filter): array
        {
        return array_map([get_parent_class($this), $filter], $sentences);
        }

    public function __call($methodName, $args)
        {
        $sentences = array_get($args, 0);
        if (is_callable([$this->filter, $methodName]) && is_array($sentences))
            {
            return array_map([$this->filter, $methodName], $sentences);
            }
        }

    public function sentenceFlow($sentences, array $flow): array
        {
        $result = [];
        foreach ($sentences as $text)
            {
            $result[] = $this->filter->flow($text, array_map(function($transformation)
                        {
                        return $transformation;
                        }, $flow));
            }
        return $result;
        }

    protected function sentencePreFilters(string $text): string
        {
        return preg_replace(// quote marks and parentheses
                '/[“"\[\]”\(\)]*/'
                , ''
                , preg_replace(// spaces
                        '/\ \ +/'
                        , ' '
                        , str_replace(// horizontal ellipsis
                                ['[...]', '...']
                                , ' '
                                , preg_replace(// single capital letters with a dot
                                        '/(\s[A-Z])\./'
                                        , '$1' . self::TEMP_SEPARATOR
                                        , $text
                                )
                        )
                )
        );
        }

    protected function sentencePostFilters(array $sentences): array
        {
        return array_map(function($e)
            {
            return trim(str_replace(self::TEMP_SEPARATOR, '.', $e));
            }, $sentences);
        }

    }
