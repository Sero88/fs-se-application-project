<?php 
class Paginator{
    private $page_count = 0;
    private $base_url = null;

    function __construct($base_url, $page_count){
        $this->set_page_count($page_count);
        $this->base_url = $base_url;
    }

    public function set_page_count($page_count){
        $this->page_count = $page_count;
    }

    public function get_page_count(){
        return $this->page_count;
    }

  

    public function show_pagination(){
        if( null === $this->base_url) return "<p> Unable to show pagination. </p>";
        for($i = 1; $i <= $this->page_count; $i++){
            ?>
                <a class="pagination-item" href="<?= $this->base_url . "?page=$i" ?>"> <?=$i?> </a>
            <?
        }
    }
}