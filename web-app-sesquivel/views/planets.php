<?php
/***
* Class to display data
***/

class Planets implements View{
    public function output($data){//handles html output
        echo '<h2> Planet Residents </h2>'; 
        ?>
            <div class="about-page"> 
                <p> About this page: outputs list of planets and its residents in raw JSON format.</p>
                <br/>
                <p> This page contains pagination: 10 planets per page </p>                
            </div>

            <section class="json-data">
                <?php
                    if(isset($_REQUEST['page'])){
                        echo "<p>Page: " . $_REQUEST['page'] . "</p><br/>";
                    }else{
                        echo "<p>Page: 1</p>";
                    }
                ?>
                <?= $data ?>
            </section>
           
        <?php
    } 
}