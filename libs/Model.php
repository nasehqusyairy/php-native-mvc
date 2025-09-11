<?php

namespace Libs;

class Model
{
    protected $tableName;
    protected $fillable = [];

    private $whereClause = '';
    private $values = [];
    private $limit;
    private $offset;

    public static function all(): array
    {
        $instance = new static;
        $query = "SELECT * FROM {$instance->tableName}";
        return DB::execute($query);
    }

    public static function find($id)
    {
        $instance = new static;
        $query = "SELECT * FROM {$instance->tableName} WHERE id = :id";
        $result = DB::execute($query, ['id' => $id]);
        return $result ? $result[0] : null;
    }

    public static function create(array $data)
    {
        $instance = new static;
        $fields = array_intersect_key($data, array_flip($instance->fillable));

        $columns = implode(", ", array_keys($fields));
        $placeholders = implode(", ", array_map(fn($f) => ":$f", array_keys($fields)));

        $query = "INSERT INTO {$instance->tableName} ($columns) VALUES ($placeholders)";
        return DB::execute($query, $fields);
    }

    public function update(array $data)
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $setClause = implode(", ", array_map(fn($f) => "$f = :$f", array_keys($fields)));

        $query = "UPDATE {$this->tableName} SET $setClause WHERE id = :id";
        $fields['id'] = $data['id'];
        return DB::execute($query, $fields);
    }

    public static function delete($id)
    {
        $instance = new static;
        $query = "DELETE FROM {$instance->tableName} WHERE id = :id";
        return DB::execute($query, ['id' => $id]);
    }

    public static function where(string $whereClause, array $values)
    {
        $instance = new static;
        $instance->whereClause = $whereClause;
        $instance->values = $values;
        return $instance;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function get()
    {
        $query = "SELECT * FROM {$this->tableName}";
        if ($this->whereClause) {
            $query .= " WHERE {$this->whereClause}";
        }

        if ($this->limit !== null) {
            $query .= " LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $query .= " OFFSET {$this->offset}";
        }

        return DB::execute($query, $this->values);
    }

    public function first()
    {
        $this->limit = 1;
        $results = $this->get();
        return $results ? $results[0] : null;
    }

    private function pagination(int $page = 1, int $limit = 10)
    {
        $this->limit = $limit;
        $this->offset = ($page - 1) * $limit;

        $data = $this->get();

        // Get total count
        $countQuery = "SELECT COUNT(*) as count FROM {$this->tableName}";
        if ($this->whereClause) {
            $countQuery .= " WHERE {$this->whereClause}";
        }
        $countResult = DB::execute($countQuery, $this->values);
        $total = $countResult[0]->count ?? 0;

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'totalPages' =>  ceil($total / $limit),
            'hasNextPage' => $page * $limit < $total,
            'hasPrevPage' => $page > 1
        ];
    }

    public function __call($name, $arguments)
    {
        if ($name === 'paginate') {
            return $this->pagination(...$arguments);
        }
    }

    public static function __callStatic($name, $arguments)
    {
        if ($name === 'paginate') {
            $instance = new static;
            return $instance->pagination(...$arguments);
        }
    }
}
