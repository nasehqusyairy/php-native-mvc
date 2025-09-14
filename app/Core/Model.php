<?php

namespace App\Core;

class Model
{
    protected $table;
    protected $fillable = [];

    private $whereClause;
    private $values = [];
    private $limit;
    private $offset;

    public static function create(array $data)
    {
        $instance = new static;
        $fields = array_intersect_key($data, array_flip($instance->fillable));
        /** PENJELASAN :
         * 
         * Kita harus memastikan bahwa hanya field yang ada di $fillable yang akan dimasukkan ke dalam query.
         * kita menggunakan array_intersect_key untuk menyaring $data berdasarkan kunci yang ada di $fillable.
         * Karena array_intersect_key membutuhkan array asosiatif, 
         * maka kita gunakan array_flip untuk membalik $fillable menjadi array asosiatif dengan nilai sebagai kunci.
         * 
         */

        $columns = implode(", ", array_keys($fields));
        $placeholders = implode(", ", array_map(fn($f) => ":$f", array_keys($fields)));
        /** PENJELASAN :
         * 
         * implode(", ", array_keys($fields)) akan menghasilkan string kolom yang dipisahkan koma.
         * implode(", ", array_map(fn($f) => ":$f", array_keys($fields))) akan menghasilkan string placeholder yang dipisahkan koma.
         * 
         * Hasilnya :
         * $columns = "name, email, password"
         * $placeholders = ":name, :email, :password"
         * 
         * Contoh query yang dihasilkan :
         * $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
         * 
         */

        $query = "INSERT INTO {$instance->table} ($columns) VALUES ($placeholders)";
        return DB::execute($query, $fields);
    }

    public function get()
    {
        $query = "SELECT * FROM {$this->table}";
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

    public static function all(): array
    {
        $instance = new static;
        return $instance->get();
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

    public function first()
    {
        $results = $this->limit(1)->offset(0)->get();
        return $results ? $results[0] : null;
    }

    public static function find($id)
    {
        return static::where('id = :id', ['id' => $id])->first();
    }

    public function update(array $data)
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $setClause = implode(", ", array_map(fn($f) => "$f = :$f", array_keys($fields)));
        /** PENJELASAN :
         * 
         * implode(", ", array_map(fn($f) => "$f = :$f", array_keys($fields))) akan menghasilkan string SET clause yang dipisahkan koma.
         * 
         * Hasilnya :
         * $setClause = "name = :name, email = :email, password = :password"
         * 
         * Contoh query yang dihasilkan :
         * $query = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
         * 
         */

        $query = "UPDATE {$this->table} SET $setClause WHERE " . $this->whereClause;
        return DB::execute($query, array_merge($fields, $this->values));
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table}" . ($this->whereClause ? " WHERE {$this->whereClause}" : "");
        return DB::execute($query, $this->values);
    }

    private function pagination(int $page = 1, int $limit = 10)
    {
        $this->limit = $limit;
        $this->offset = ($page - 1) * $limit;

        $data = $this->get();

        // Get total count
        $countQuery = "SELECT COUNT(*) as count FROM {$this->table}";
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
