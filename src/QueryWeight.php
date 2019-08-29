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

    public function getQueryWeight($query)
    {
        if(!$this->isExplainable($query)) {
            throw new UnexplainableQueryException();
        }
        $explain = "EXPLAIN " . $query;
        $statement = $this->pdo->query( $explain );
        $rows = $statement->fetchAll();
        $total = 1;
        $computableRows = 0;
        foreach( $rows as $row )
        {
            $complexity = $row['rows'];
            if ($complexity > 1) {
                $computableRows++;
            }
            $total *= $complexity;
        }
        return $computableRows > 1 ? $total : 1;
    }

    protected function isExplainable($query)
    {
        return (preg_match('/^\s*SELECT\s+/i',$query));
    }
}
