<?php
/***
* Class to display data
***/


class Characters implements View{
    public function output($data){//handles html output
     
        $this_url = 'http://' . $_SERVER['HTTP_HOST'] . "/web-app-sesquivel/characters";
       
        echo '<h2> Characters </h2>'; 
        ?>
            <div class="about-page"> 
                <p> About this page: Outputs a list of 50 Characters. ***When sorted outputs all characters.*** <br /> 
                Click on a Character block to get more info about the character.</p>
                <br />   
                <p> Sort by Height: <a href="<?= $this_url .'?sort=height&order=desc' ?>"> DESC </a> | <a href="<?= $this_url . '?sort=height&order=asc'?>"> ASC </a> </p>
                <p> Sort by Mass: <a href="<?= $this_url .'?sort=mass&order=desc' ?>"> DESC </a> | <a href="<?= $this_url . '?sort=mass&order=asc'?>"> ASC </a> </p>
            </div>
           
        <?php

        $counter = 1;
        foreach($data as $key => $char_array){
            
            $name = $char_array['name'];
            $url_name = urlencode($name);
            $mass = $char_array['mass'];
            $height = $char_array['height'];

            ?>
        
            <a class="char-block" href="/web-app-sesquivel/character?indiv_item=<?=$url_name?>"> 
                <p class="number"> <?= $counter++?> </p>
                <div class="char-info-section">
                    <p class="char-name"><?= $name ?></p>
                    <p>Mass: <?= $mass ?> </p>
                    <p>Height: <?= $height ?> </p>                    
                </div>
            </a>
            <?php

            

        } 
       
    }
}