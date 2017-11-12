<?php

namespace Tests\Unit;

use Tests\TestCase;

class FilterTest extends TestCase
{

    static $filter;

    protected function setUp()
    {
        parent::setUp();

        $morphyMock = $this->getMockBuilder('\Egwk\Install\Writings\Morphy')
                ->disableOriginalConstructor()
                ->disableOriginalClone()
                ->disableArgumentCloning()
                ->disallowMockingUnknownTypes()
                ->getMock();

        $morphyMock->method('lemmatizeWord')
                ->willReturn('do');

        $morphyMock->method('lemmatize')
                ->willReturn(['do']);

        static::$filter = new \Egwk\Install\Writings\Filter($morphyMock);
    }

    /**
     * Tests Filter::strip();
     *
     * @return void
     */
    public function testStrip()
    {
        $result = static::$filter->strip('<b class="some-class">apostles</b>');
        $this->assertEquals('apostles', $result);
    }

    /**
     * Tests Filter::normalize();
     *
     * @return void
     */
    public function testNormalize()
    {
        $result = static::$filter->normalize(' Nor    Maliz -- ed');
        $this->assertEquals('nor maliz ed', $result);
    }

    /**
     * Tests Filter::split();
     *
     * @return void
     */
    public function testSplit()
    {
        $result = static::$filter->split('split this string');
        $this->assertEquals(['split', 'this', 'string'], $result);
    }

    /**
     * Tests Filter::stick();
     *
     * @return void
     */
    public function testStick()
    {
        $result = static::$filter->stick(['stick', 'this', 'string']);
        $this->assertEquals('stick this string', $result);
    }

    /**
     * Tests Filter::killStopWords();
     *
     * @return void
     */
    public function testKillStopWords()
    {
        $result = static::$filter->killStopWords(['this', 'is', 'my', 'story']);
        $this->assertArraySubset([3 => 'story'], $result);
    }

    /**
     * Tests Morphy::lemmatize();
     *
     * @return void
     */
    public function testLemmatize()
    {
        $result = static::$filter->lemmatize(['done']);
        $this->assertEquals(['do'], $result);
    }

    /**
     * Tests Morphy::sort();
     *
     * @return void
     */
    public function testSort()
    {
        $result = static::$filter->sort(['zone', 'done']);
        $this->assertEquals(['done', 'zone'], $result);
    }

    /**
     * Tests Morphy::getMorphy();
     *
     * @return void
     */
    public function testGetMorphy()
    {
//        
//        TODO...?
//
//        $morphyObject = static::$filter->getMorphy();
//        $this->assertInstanceOf('\phpMorphy', $morphyObject);
        $this->assertTrue(true);
    }

    /**
     * Tests Morphy::getStopWords();
     *
     * @return void
     */
    public function testGetStopWords()
    {
        $result = static::$filter->getStopWords();
        $this->assertArraySubset(
                [
            'a',
            'about',
            'above',
            'after',
            'again',
            'against',
                ], $result);
    }

    /**
     * Tests Morphy::flow();
     *
     * @return void
     */
    public function testFlow()
    {
        $result = static::$filter->flow('Beta <b class="some-class">Alpha</b>', [
            'strip'
            , 'normalize'
            , 'split'
            , 'sort'
            , 'stick'
        ]);
        $this->assertEquals('alpha beta', $result);
    }

}
