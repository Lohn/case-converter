<?php

use Jawira\CaseConverter\Split\SpaceSplitter;
use PHPUnit\Framework\TestCase;

class SpaceSplitterTest extends TestCase
{
    /**
     * @covers \Jawira\CaseConverter\Split\SpaceSplitter::split
     * @throws \ReflectionException
     * @throws \Jawira\CaseConverter\CaseConverterException
     */
    public function testSplit()
    {
        // Disabling constructor with one stub method
        $mock = $this->getMockBuilder(SpaceSplitter::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['splitUsingPattern'])
                     ->getMock();

        // Setting value to protected property
        $reflectionObject   = new ReflectionObject($mock);
        $reflectionProperty = $reflectionObject->getProperty('inputString');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($mock, 'dummyString');

        // Configuring stub
        $mock->expects($this->once())
             ->method('splitUsingPattern')
             ->with('dummyString', '# +#u')
             ->willReturn(['dummy', 'array']);

        /** @var \Jawira\CaseConverter\Split\SpaceSplitter $mock */
        $returned = $mock->split();
        $this->assertSame(['dummy', 'array'], $returned);
    }
}
