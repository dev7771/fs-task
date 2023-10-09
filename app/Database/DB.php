<?php

namespace App\Database;

class DB
{
    protected $table;
    protected $whereClauses = [];
    protected $orWhereClauses = [];
    protected $selectColumns = ['*'];
    protected $orderByColumn;
    protected $orderByDirection;


    /**
     * @param $table
     * @return DB
     */
    public static function table($table)
    {
        $instance = new self();
        $instance->table = $table;
        return $instance;
    }


    /**
     * @param $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($column, $operator = '=', $value = null)
    {

        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }

        if($value === null)
            $operator = 'is NULL';


        $this->whereClauses[] = "$column $operator $value";
        return $this;
    }


    /**
     * @param $callback
     * @return $this
     */
    public function orWhere($callback)
    {
        $subquery = new self();
        $callback($subquery);
        $this->orWhereClauses[] = "(" . implode(" AND ", $subquery->whereClauses) . ")";
        return $this;
    }


    /**
     * @param ...$columns
     * @return $this
     */
    public function select(...$columns)
    {
        if (!empty($columns)) {
            $this->selectColumns = $columns;
        }
        return $this;

    }


    /**
     * @param $column
     * @param $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderByColumn = $column;
        $this->orderByDirection = $direction;
        return $this;
    }


    /**
     * @return string
     */
    public function toSql()
    {
        $sql = "SELECT " . implode(", ", $this->selectColumns) . " FROM " . $this->table;

        if (!empty($this->whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $this->whereClauses);
        }
        if (!empty($this->orWhereClauses)) {
            $sql .= " OR " . implode(" OR ", $this->orWhereClauses);
        }

        if (!empty($this->orderByColumn)) {
            $sql .= " ORDER BY " . $this->orderByColumn . " " . $this->orderByDirection;
        }

        return $sql;
    }
}