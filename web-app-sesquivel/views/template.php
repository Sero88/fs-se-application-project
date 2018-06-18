<?php 
/***
* Class to display template elements (shared throughout web app)
***/
class Template{
    public function get_header(){
        ?>
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8" />
                    <title> FS - Software Engineering Application Projection </title>
                    <link href="http://<?=$_SERVER['HTTP_HOST']?>/web-app-sesquivel/css/main.css" rel="stylesheet" type="text/css"/>
                    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400" rel="stylesheet">                           
                </head>

                <body>
                    <header>
                        <div class="title-section">
                            <h1> <a href="http://<?=$_SERVER['HTTP_HOST']?>/web-app-sesquivel/"> Software Engineering Application Project </a> </h1>
                            <p class="author"> <a href="http://esergio.com"> Sergio Esquivel </a> </p>
                        </div>

                        <nav>
                            <a href="/web-app-sesquivel/characters"> Characters </a>
                            <a href="/web-app-sesquivel/planet-residents"> Planet Residents </a> |
                            <form class="search-char-form" action="/web-app-sesquivel/character" method="post">Character Search: <input type="text" placeholder="name" name="indiv_item">
                        </nav>

                    </header>
                    <main>
        <?php
    }

    public function get_footer(){
        ?>       
                    </main>     
                    <footer> 
                        <p>Copyright &copy; Sergio Esquivel</p>
                        <p>Data Retrieved using: <a href="https://swapi.co/">SWAPI.CO</a></p>
                        <p><em>Commercial logos, images and brands belong to their respective owners.  I do not claim ownership nor any affiliation.</em>

                    </footer>
                </body>
            </html>
        <?php
    }
}
