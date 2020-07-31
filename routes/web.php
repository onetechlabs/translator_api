<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//Auth
$router->post("/user-login", "AuthController@userLogin");
$router->post("/user-create", "AuthController@userCreate");
$router->post("/user-change-password", ['middleware' => 'if_userlogin', 'uses' => "AuthController@userChangePassword"]);
$router->post("/translate", "TranslatorController@translateResults");

//Search Engine List
$router->post("/language-lists", ['middleware' => 'if_userlogin', 'uses' => "TranslatorController@language_lists"]);
$router->post("/language-list/show/{id}", ['middleware' => 'if_userlogin', 'uses' => "TranslatorController@language_listShow"]);
$router->post("/language-list/create", ['middleware' => 'if_userlogin', 'uses' => "TranslatorController@language_listCreate"]);
$router->post("/language-list/update/{id}", ['middleware' => 'if_userlogin', 'uses' => "TranslatorController@language_listUpdate"]);
$router->post("/language-list/delete/{id}", ['middleware' => 'if_userlogin', 'uses' => "TranslatorController@language_listDelete"]);
