<?php

/***
* Class to display homepage
***/

class main implements View{
    public function output($data, $paginator = false){
        ?>
            <div class="fullwidth-display">
                <section class="page-section">
                    <a class="tab character-tab" href="/web-app-sesquivel/characters/"></a>
                    <h2><a class="section-title" href="/web-app-sesquivel/characters/">Characters</a></h2>   
                </section>
                <section class="page-section">
                    <a class="tab planets-tab" href="/web-app-sesquivel/planet-residents/"></a>
                    <h2><a class="section-title" href="/web-app-sesquivel/planet-residents/">Planet Residents</a></h2>
                </section>
            </div>
        <?php
    }
}