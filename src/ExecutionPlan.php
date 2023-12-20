<?php

namespace QueryWeight;

class ExecutionPlan
{
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
    }
}
