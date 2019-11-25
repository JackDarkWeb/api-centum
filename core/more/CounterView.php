<?php


class CounterView extends Model
{
    private $file = 'data/counter.txt';
    protected $table = 'users';
    /**
     * @param $ip_addr
     * @param $article_id
     */
    function add_view($ip_addr, $article_id){

        $date = time();
        $row = $this->builderGet( ['ip_addr','=', $ip_addr, 'AND', 'announce_id', '=', $article_id])
                    ->count();

        if($row === 0){
            $this->insert(['ip_addr' => $ip_addr, 'announce_id' => $article_id, 'created_at' => $date]);
        }

    }

    /**
     * @param $article_id
     * @return string
     */
    function number_views($article_id):string {

        switch ($this->counter($article_id)){
            case 0:
                return '0 vue';
                break;
            case 1:
                return '1 vue';
                break;
            default:
                return $this->counter($article_id).' vues';
                break;
        }
    }

    /**
     * @param $article_id
     * @return mixed
     */
    function counter($article_id){
        return $this->get(['announce_id','=', $article_id])
                    ->count();
    }
}