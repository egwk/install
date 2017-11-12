<?php

namespace Tests\Unit;

use Tests\TestCase;

class MorphyTest extends TestCase
{

    static $morphy;

    protected function setUp()
    {
        static::$morphy = new \Egwk\Install\Writings\Morphy('./data/phpmorphy/en');
    }

    /**
     * Tests Morphy::lemmatizeWord();
     *
     * @return void
     */
    public function testLemmatizeWord()
    {
        $result = static::$morphy->lemmatizeWord('Apostles');
        $this->assertEquals('apostle', $result);
    }

    /**
     * Tests Morphy::lemmatize();
     *
     * @return void
     */
    public function testLemmatize()
    {
        $result = static::$morphy->lemmatize(['Apostles', 'ACTS', 'done']);
        $this->assertEquals(['apostle', 'act', 'do'], $result);
    }

    /**
     * Tests Morphy::getMorphy();
     *
     * @return void
     */
    public function testGetMorphy()
    {
        $getMorphy    = $this->getMethod('\Egwk\Install\Writings\Morphy', 'getMorphy');
        $morphyObject = $getMorphy->invoke(static::$morphy);
        $this->assertInstanceOf('\phpMorphy', $morphyObject);
    }

}
