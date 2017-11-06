<?php

namespace Egwk\Install\Writings;

use phpMorphy,
    phpMorphy_FilesBundle;

/**
 * Description of WritingsMorph
 *
 * @author Peter
 */
class Morphy
    {

    protected $morphy   = null;
    protected $dictPath = null;

    public function __construct(string $dictPath = "./")
        {
        $this->dictPath = $dictPath;
        }

    public function lemmatize(array $words)
        {
        $result = [];
        foreach ($words as $word)
            {
            $result[] = $this->lemmatizeWord($word);
            }
        return $result;
        }

    public function lemmatizeWord($word)
        {
        $tmp = $this->getMorphy()->lemmatize(strtoupper($word), phpMorphy::NORMAL);
        return strtolower($tmp !== false ? array_pop($tmp) : $word);
        }

    protected function getMorphy()
        {
        if (null === $this->morphy)
            {
            $dictBundle = new phpMorphy_FilesBundle($this->dictPath, 'eng');
            try
                {
                $this->morphy = new phpMorphy($dictBundle, [
                    'storage'           => PHPMORPHY_STORAGE_FILE,
                    'with_gramtab'      => false,
                    'predict_by_suffix' => true,
                    'predict_by_db'     => true
                ]);
                }
            catch (phpMorphy_Exception $e)
                {
                die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
                }
            }
        return $this->morphy;
        }

    }
