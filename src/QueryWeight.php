<?php
namespace QueryWeight;

use PDO;

class QueryWeight
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getExecutionPlan($query)
    {
        if(!$this->isExplainable($query)) {
            throw new UnexplainableQueryException();
        }

        $executionPlan = (new ExecutionPlan())->setQuery($query);

        $statement = $this->pdo->query("EXPLAIN " . $query);
        $rows = $statement->fetchAll();
        $statement->closeCursor();
        $executionPlan->setRows($rows);
        $executionPlan->setComputableRows($this->getComputableRows($rows));
        // This may not work with older mysqls
        try {
            $statement = $this->pdo->query("EXPLAIN FORMAT=JSON " . $query);
            $row = $statement->fetch();
            $executionPlan->setJson($row['EXPLAIN']);
            $statement->closeCursor();
        } catch (\PDOException $ex) {
        }

        return $executionPlan;
    }

    private function getComputableRows(array $rows)
    {
        $total = 1;
        $computableRows = 0;
        foreach ($rows as $row)
        {
            $complexity = $row['rows'];
            if ($complexity > 1) {
                $computableRows++;
            }
            $total *= $complexity;
        }
        return $computableRows > 1 ? $total : 1;
    }


    public function getQueryWeight($query)
    {
        return $this->getExecutionPlan($query)->getComputableRows();
    }

    protected function isExplainable($query)
    {
        return (preg_match('/^\s*SELECT\s+/i',$query));
    }
}
