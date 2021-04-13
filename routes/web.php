<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [
    'as'    => 'home',
    'uses'  => 'PageController@index'
]);

Route::group(['namespace' => 'Ajax'], function(){
    // admin
    Route::get('ajax/admin/{action}', [
        'uses' => 'AdminController@index'
    ])->where(['action' => '[a-zA-Z_]+']);
    
    // feedback
    Route::post('ajax/send/contact', [
        'uses' => 'SendController@contact'
    ]);
    
    Route::post('ajax/send/job', [
        'uses' => 'SendController@job'
    ]);
});

// articles

Route::get('news', [
	'uses' => 'NewsController@index'
]);

Route::get('news/page-{page}', [
	'uses' => 'NewsController@index'
])->where('page', '[0-9]+');

Route::get('news/{uri}', [
	'uses' => 'NewsController@once'
])->where('uri', '[a-zA-Z_0-9\-]+');

//

Route::get('{lang}/news', [
	'uses' => 'NewsController@index'
])->where('lang', '[a-z{2,2}]+');

Route::get('{lang}/news/page-{page}', [
	'uses' => 'NewsController@index'
])->where('page', '[0-9]+')->where('lang', '[a-z{2,2}]+');

Route::get('{lang}/news/{uri}', [
	'uses' => 'NewsController@once'
])->where('uri', '[a-zA-Z_0-9\-]+')->where('lang', '[a-z{2,2}]+');

//

Route::get('/{lang}/{uri}', [
	'uses' => 'PageController@once'
])->where('lang', '[a-z{2,2}]+')->where('uri', '[a-zA-Z_0-9\-]+');

Route::get('/{lang}', [
	'uses' => 'PageController@index'
])->where('lang', '[a-z]{2,2}');

Route::get('/{uri}', [
	'uses' => 'PageController@once'
])->where('uri', '[a-zA-Z_0-9\-{3,}]+');
