<?php


abstract class Model extends Db
{
    private $query,
            $error = false,
            $results,
            $count = 0;


    #The name of the table in the database
    # The name of the table must always be plural
    protected $table            = false,
              $join_table       = false,
              $pivot_table      = false,

              $table_id         = false,
              $join_table_id    = false;



    function __construct()
    {
        if($this->table === false){
            $this->table      = strtolower(get_class($this)).'s';
            $this->table_id   = strtolower(get_class($this)).'_id';
        }
    }


    /**
     * @param $sql
     * @param array $params
     * @return $this
     */
    function query($sql, $params = [])
    {

        $this->error = false;
        if($this->query = $this->getInstance()->prepare($sql))
        {
            $x = 1;
            if(count($params))
            {
                foreach ($params as $param)
                {
                    $this->query->bindValue($x, $param);
                    $x++;
                }
            }
        }
        if($this->query->execute()){
            $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
            $this->count   = $this->query->rowCount();
        }else
        {
            $this->error = true;
        }
        return $this;
    }

    /**
     * @param $action
     * @param $table
     * @param array $where
     * @return $this|bool
     */

    // [ORDER BY id DESC]
    private function action($action, $table, $where = []){

        if(gettype($where) == 'array' && count($where) === 3){

            $operators = ['=', '<', '>', '<=', '>='];

            $field    = $where[0];
            $operator = $where[1];
            $value    = $where[2];

            if(in_array($operator, $operators)){

                $sql = "{$action} FROM (({$table} JOIN {$this->pivot_table} ON  {$table}.id = {$this->pivot_table}.{$this->table_id})  JOIN {$this->join_table} ON {$this->join_table}.id = {$this->pivot_table}.{$this->join_table_id}) WHERE {$this->table}.{$field} {$operator} ? ORDER BY {$table}.created_at DESC";

                if(!$this->query($sql, [$value])->error()){

                    return $this;
                }
            }
        }else{

            $sql = "{$action} FROM (({$table}  JOIN {$this->pivot_table} ON  {$table}.id = {$this->pivot_table}.{$this->table_id})  JOIN {$this->join_table} ON {$this->join_table}.id = {$this->pivot_table}.{$this->join_table_id}) ORDER BY {$table}.created_at DESC";
            //return $sql;
            if(!$this->query($sql, [])->error()){

                return $this;
            }

        }
        return false;
    }

    // [ORDER BY id DESC]
    private function action_delete($action, $table, $where = []){

        if(gettype($where) == 'array' && count($where) === 3){

            $operators = ['=', '<', '>', '<=', '>='];

            $field    = $where[0];
            $operator = $where[1];
            $value    = $where[2];

            if(in_array($operator, $operators)){

                $sql = "{$action} FROM {$table}  WHERE {$field} {$operator} ?";

                if(!$this->query($sql, [$value])->error()){

                    return $this;
                }
            }
        }
        return false;
    }

    private function action_get_last_id($action, $table){

        $sql = "{$action} FROM {$table} ORDER BY id DESC";

        if(!$this->query($sql, [])->error()){

            return $this;
        }
        return false;
    }


    /**
     * @return mixed
     */
    function all(){

        return $this->action("SELECT category_name, {$this->table}.id, product_name, description, price, {$this->table}.created_at, {$this->table}.updated_at ", $this->table)
                    ->results();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->action("SELECT category_name, {$this->table}.id, product_name, description, price, {$this->table}.created_at, {$this->table}.updated_at ", $this->table, ["id", '=', $id])
                    ->results();
    }

    /**
     * @param $where
     * @return mixed
     */
    public function findByName($where)
    {

        return $this->action("SELECT category_name, {$this->table}.id, product_name, description, price, {$this->table}.created_at, {$this->table}.updated_at ", $this->table, $where)
                    ->results();
    }



    /**
     * @param array $fields
     * @return bool
     */
    function insert($fields = []){

        $keys   = array_keys($fields);
        $values = '';
        $x      = 1;

        foreach ($fields as $field){

            $values .=  '?';

            if($x < count($fields)){

                $values .= ', ';
            }
            $x++;
        }

        $sql  = "INSERT INTO {$this->table}(`".implode('`,`', $keys)."`) VALUES({$values})";

        //return $fields; die(1);
        if(!$this->query($sql, $fields)->error()){

            return true;
        }

        return false;
    }




    /**
     * @param $where
     * @param array $fields
     * @return bool
     */
    function update($where, $fields = []){

        $column  = $where[0];
        $val     = (is_numeric($where[1])) ? $where[1] : "'$where[1]'";

        $set = '';
        $x   =  1;

        foreach ($fields as $name => $value){

            $set .= "{$this->table}.{$name} = ?";

            if($x < count($fields)){

                $set .= ', ';
            }
            $x++;
        }


        $sql = "UPDATE (({$this->table} JOIN {$this->pivot_table} ON  {$this->table}.id = {$this->pivot_table}.{$this->table_id})  JOIN {$this->join_table} ON {$this->join_table}.id = {$this->pivot_table}.{$this->join_table_id}) SET {$set}  WHERE {$this->table}.{$column} = $val";


        if(!$this->query($sql, $fields)->error()){

            return true;
        }

        return false;
    }

    /**
     * @param $id
     * @return $this|bool
     */
    function delete($id){
        return $this->action_delete('DELETE ', $this->table, ["id", '=', $id]);
    }


    /**
     * @return mixed
     */
    function results(){

        return $this->results;
    }

    /**
     * @return int
     */
    function count(){

        return $this->count;
    }

    /**
     * @return mixed
     */
    function first(){

        return $this->results()[0];
    }

    /**
     * @return bool
     */
    function error()
    {
        return $this->error;
    }

    /**
     * @param $name
     * @param $args
     */
    public function __call($name, $args){

        echo $name," doesn't exist in this class";
    }

    function lastId(){
        return $this->action_get_last_id('SELECT id ', $this->table)
                    ->first()
                    ->id;
    }




    function test($id){
        $query = $this->getInstance()->prepare("SELECT * FROM ((products
        join category_products  on products.id = category_products.product_id) 
       join categories  on categories.id = category_products.category_id) WHERE products.id = ?");

        $query->execute(array($id));
        return $result = $query->fetchAll();
    }

    function test_update($id, $fields){
        $query = $this->getInstance()->prepare("UPDATE (({$this->table} JOIN {$this->pivot_table} ON  {$this->table}.id = {$this->pivot_table}.{$this->table_id})  JOIN {$this->join_table} ON {$this->join_table}.id = {$this->pivot_table}.{$this->join_table_id}) SET product_name = ?, description = ? , price = ?  WHERE {$this->table}.id = $id");

        $query->execute((array_values($fields)));

    }

    function test_delete($id){
        $query = $this->getInstance()->prepare("DELETE FROM {$this->table}  WHERE id = $id");
        //return $query;
        $query->execute((array($id)));
        return true;
    }

}