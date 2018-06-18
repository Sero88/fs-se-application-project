<?php
/***
* Class to display data
***/

class Character implements View{
    public function output($data){//handles html output
        ?>
            <div class="indiv-char-block">                 
             <div class="char-info-section">
        <?php
            if( isset($data['error']) ){
                echo $data['error'];
            }
            else{
                foreach($data as $key => $val){
                
                    if(is_array($val)){
                        echo "<p class='info-label'> $key: </p> <ul>";
                        foreach($val as $sub_key => $sub_val){
                            echo "<li> $sub_val </li>";
                        }
                        echo '</ul>';
                    }else{
                        echo '<p> <span class="info-label">' . $key . "</span> : " . $val . '</p>';
                    }
                } 
            }
        ?>
             </div>
            </div>
            <?php
    
        
    } 
}