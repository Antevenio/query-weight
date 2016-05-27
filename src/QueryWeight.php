<?php
namespace QueryWeight;

use PDO;

class QueryWeight
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct( PDO $pdo )
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
        foreach( $rows as $row )
        {
            $total *= $row['rows'];
        }
        return ($total);
    }

    protected function isExplainable($query)
    {
        return (preg_match('/^\s*SELECT\s+/i',$query));
    }
}