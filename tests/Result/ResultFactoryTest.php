<?php

namespace RunetId\Client\Result;

use PHPUnit\Framework\TestCase;
use RunetId\Client\Fixtures\Result\TestResult;

final class ResultFactoryTest extends TestCase
{
    public function testCreate()
    {
        $data = ['Id' => 1];

        /** @var TestResult $result */
        $result = ResultFactory::create($data, TestResult::class);

        $this->assertInstanceOf(TestResult::class, $result);
        $this->assertSame(1, $result->Id);
    }

    public function testCreateArray()
    {
        $data = [
            'a' => [
                ['Id' => 1],
                ['Id' => 2],
            ],
            'b' => [
                ['Id' => 3],
                'x' => ['Id' => 4],
                ['Id' => 5],
            ],
        ];

        /** @var TestResult[][] $result */
        $result = ResultFactory::create($data, TestResult::class.'[][]');

        $this->assertSameSize($data, $result);
        $this->assertSame(array_keys($data), array_keys($result));

        foreach ($data as $key => $dataValue) {
            $resultValue = $result[$key];

            $this->assertSameSize($dataValue, $resultValue);
            $this->assertSame(array_keys($dataValue), array_keys($resultValue));

            foreach ($dataValue as $itemKey => $itemData) {
                $this->assertSame($itemData['Id'], $resultValue[$itemKey]->Id);
            }
        }
    }

    public function testGenerator()
    {
        $result = ResultFactory::create($this->generateItems(), TestResult::class.'[]');

        $this->assertInstanceOf(\Generator::class, $result);
        $this->assertContainsOnlyInstancesOf(TestResult::class, $result);
    }

    public function testMap()
    {
        $collectionData = [[], []];

        /** @var TestResult $result */
        $result = ResultFactory::create(['Collection' => $collectionData], TestResult::class);

        $this->assertSameSize($collectionData, $result->Collection);
        $this->assertContainsOnlyInstancesOf(TestResult::class, $result->Collection);
    }

    public function testNullability()
    {
        /** @var TestResult $result */
        $result = ResultFactory::create(null, TestResult::class);

        $this->assertNull($result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Class NS\NonExistingClass does not exist.
     */
    public function testNonExistingClass()
    {
        ResultFactory::create([], 'NS\NonExistingClass');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Result class PHPUnit\Framework\TestCase must extend RunetId\Client\Result\AbstractResult.
     */
    public function testInvalidClass()
    {
        ResultFactory::create([], TestCase::class);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected null or array, string given.
     */
    public function testInvalidDataType()
    {
        ResultFactory::create('', TestCase::class);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected array or generator, string given.
     */
    public function testInvalidCollectionType()
    {
        ResultFactory::create('', TestCase::class.'[]');
    }

    private function generateItems()
    {
        foreach (range(1, 10) as $id) {
            yield ['Id' => $id];
        }
    }
}
