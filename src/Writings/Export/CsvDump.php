<?php

namespace Egwk\Install\Writings\Export;

use Egwk\Install\Writings\Filter;

/**
 * Dumps CSV data
 *
 * @author Peter
 */
class CsvDump extends File
    {

    use Egwk\Install\Writings\Tools\Csv;

    const MODIFIER_PARAGRAPHS = "paragraphs";
    const MODIFIER_WORDS      = "words";
    const MODIFIER_SENTENCES  = "sentences";

    protected $parents = [1 => '', '', '', '', '', ''];

    public function __construct(Filter $filter)
        {
        parent::__construct($filter, storage_path("egwwritings.csv"));
        }

    protected function clearParents(&$parents, $elementLevel)
        {
        for ($i = $elementLevel; $i <= 6; $i++)
            {
            $parents[$i] = '';
            }
        }

    protected function getElementLevel(string $elementType): int
        {
        if (preg_match('/h[1-6]/', $elementType))
            {
            return (int) substr($elementType, 1, 1);
            }
        return 7;
        }

    protected function initOutputFile($outputFile = "./data")
        {
        $this->outputFile = $outputFile;
        $this->resetOutputFile(self::MODIFIER_PARAGRAPHS);
        $this->resetOutputFile(self::MODIFIER_WORDS);
        $this->resetOutputFile(self::MODIFIER_SENTENCES);
        }

    protected function words($paragraph)
        {
        $words = $this->chainFilter
                ->set($paragraph->content)
                ->strip()
                ->normalize()
                ->split()
                ->killStopWords()
                ->lemmatize()
                ->sort()
                ->stick()
                ->get();
        return $words;
        }

    protected function sentences($paragraph)
        {
        $sentences = $this->sentenceFilter->splitIntoSentences($this->filter->strip($paragraph->content));
        return $sentences;
        }

    protected function sentenceWordLists(array $sentences)
        {
        $sentenceWordLists = $this->sentenceChainFilter
                ->set($sentences)
                ->strip()
                ->normalize()
                ->split()
                ->killStopWords()
                ->lemmatize()
                ->sort()
                ->stick()
                ->get();
        return $sentenceWordLists;
        }

    public function export($paragraph)
        {
        $words             = $this->words($paragraph);
        $wordList          = str_replace(' ', "\n", $words) . "\n";
        $this->writeOutputFile($wordList, self::MODIFIER_WORDS);
        $sentences         = $this->sentences($paragraph);
        $sentenceWordLists = $this->sentenceWordLists($sentences);
        foreach ($sentences as $k => $sentence)
            {
            $sentenceWordList = array_get($sentenceWordLists, $k, "");
            $csvSentenceRow   = $this->createCsv('|', $paragraph->para_id, ($k + 1), $sentence, $sentenceWordList);
            $this->writeOutputFile($csvSentenceRow, self::MODIFIER_SENTENCES);
            }
        $level                 = $this->getElementLevel($paragraph->element_type);
        $this->clearParents($this->parents, $level);
        $csvRow                = $this->createCsv('|', $paragraph, $this->parents, $words);
        $this->writeOutputFile($csvRow, self::MODIFIER_PARAGRAPHS);
        $this->parents[$level] = $paragraph->para_id;
        }

    }
