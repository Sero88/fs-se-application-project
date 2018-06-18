<?php
/***
* This interface is to add global must-use methods for all views. 
* The way these methods are built will be different for each template, but the main web app requires the use of the method output()
* Global view methods that will be the same for all views use a parent class
***/

interface View{
    public function output($data); //handles html output
}