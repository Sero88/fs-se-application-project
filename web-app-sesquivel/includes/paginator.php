<?php 
/*
* Class sets pagination based on page count
* author: Sergio Esquivel
*/
class Paginator{
    private $page_count = 0;
    private $base_url = null;

    function __construct($base_url, $page_count){
        $this->set_page_count($page_count);
        $this->base_url = $base_url;
    }

    //setter function
    public function set_page_count($page_count){
        $this->page_count = $page_count;
    }

    //getter function
    public function get_page_count(){
        return $this->page_count;
    }

    /**
    * Shows the pagination based on the page count given in the contructor
    * @return void - but echoes actual pagination
    */
    public function show_pagination(){
        if( null === $this->base_url) return "<p> Unable to show pagination. </p>";
        for($i = 1; $i <= $this->page_count; $i++){
            ?>
                <a class="pagination-item" href="<?= $this->base_url . "?page=$i" ?>"> <?=$i?> </a>
            <?
        }
    }
}