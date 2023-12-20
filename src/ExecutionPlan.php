<?php

namespace QueryWeight;

class ExecutionPlan implements \JsonSerializable
{
    private $query;
    private $computableRows;
    private $rows;
    private $json;

    /**
     * @return mixed
     */
    public function getComputableRows()
    {
        return $this->computableRows;
    }

    /**
     * @param mixed $computableRows
     */
    public function setComputableRows($computableRows)
    {
        $this->computableRows = $computableRows;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param mixed $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param mixed $json
     */
    public function setJson($json)
    {
        $this->json = $json;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
