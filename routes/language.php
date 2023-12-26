<?php

/*
|--------------------------------------------------------------------------
| RUTAS DE LENGUAJE
|--------------------------------------------------------------------------
|
*/

// Datatables language
Route::get('api/lang/javascript/{item}',function($item){
    return trans('javascript.'.$item);
});