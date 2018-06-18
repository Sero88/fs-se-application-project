<?php
/***
* This interface is to add global must-use methods for all views. 
* The way these methods are built will be different for each template, but the main web app requires the use of the method output()
* Global view methods that will be the same for all views use a parent class
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