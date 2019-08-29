<?php
namespace QueryWeightTest;

use PDO;
use PDOStatement;
use PHPUnit_Framework_MockObject_MockObject;
use QueryWeight\QueryWeight;

class QueryWeightTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryWeight
     */
    protected $sut;
    /**
     * @var PDO | PHPUnit_Framework_MockObject_MockObject
     */
    protected $pdo;
    /**
     * @var PDOStatement | PHPUnit_Framework_MockObject_MockObject
     */
    protected $statement;

    public function setUp()
    {
        $this->pdo = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->statement = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new QueryWeight($this->pdo);
    }

    /**
     * @expectedException \QueryWeight\UnexplainableQueryException
     */
    public function testGetQueryWeightShouldThrowUnexplainableExceptions()
    {
        $this->setExpectedExceptionFromAnnotation();
        $this->sut->getQueryWeight("update x set y = z");
    }

    public function getQueryWeightDataProvider()
    {
        return [
            [
                [
                    ['rows' => 3829],
                    ['rows' => 1],
                    ['rows' => 1]
                ],
                1
            ],
            [
                [
                    ['rows' => 3829],
                    ['rows' => 1],
                    ['rows' => 2]
                ],
                7658
            ]
        ];
    }

    /**
     * @dataProvider getQueryWeightDataProvider
     */
    public function testGetQueryWeight($queryResult, $expectedComplexity)
    {
        $query = "select a";

        $this->pdo->expects($this->once())
            ->method("query")
            ->with($this->equalTo("EXPLAIN $query"))
            ->will($this->returnValue($this->statement));

        $this->statement->expects($this->once())
            ->method("fetchAll")
            ->will($this->returnValue($queryResult));

        $this->assertEquals($expectedComplexity, $this->sut->getQueryWeight($query));
    }
}
