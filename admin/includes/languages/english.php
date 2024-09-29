<?php

function lang($phrase){

    static $lang=array(

        // Homepage

        

        'HOME'         =>  'Home',
        'CATEGORIES'   =>  'Categories',
        'ITEMS'        =>  'Items',
        'MEMBERS'      =>  'Members',
        'STATISTICS'   =>  'Statistics',
        'LOGS'         =>  'Logs',
         ''     =>    '' ,
         ''     =>    '' ,
         ''     =>    '' ,
         ''     =>    '' ,
         ''     =>    '' ,
         ''     =>    '' , 
         






        //settings

    );

    return $lang[$phrase];

}