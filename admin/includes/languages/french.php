<?php

function lang($phrase){

    static $lang=array(

        'MESSAGE' => 'BIENVENUE',

        'ADMIN' =>'ADMINISTRATEUR'

    );

    return $lang[$phrase];

}