<?php

Route::domain(env('FEEDBACK_DOMAIN'))->middleware(['web'])->namespace('Feedback\Controllers')->name('feedback.')->group(function () {
    Route::get('/', function(){
        return redirect(_route('feedback.leads.index'));
    });

    Route::get('cookie/{session}', function($session){
        if(!\Auth::check()){
            \Session::setId($session);
            \Session::start();
        }
    })->name('cookie.create');

    Route::middleware(['auth'])->group(function(){
        Route::match(['get', 'post'], 'leads', 'LeadController@index')->name('leads.index');

        Route::middleware(['can:feedback,App\Lead\Lead'])->group(function(){
            Route::post('leads/{lead}/questionnaire', 'LeadController@questionnaire')->name('leads.questionnaire');
            Route::get('leads/{lead}', 'LeadController@show')->middleware('questionnaire')->name('leads.show');
        });

        Route::middleware(['UserAccess'])->group(function(){
            Route::get('client/', 'ClientController@index')->name('client.index');
            Route::get('client/{client}', 'ClientController@campaigns')->name('client.campaigns');
            Route::get('campaign/{campaign}', 'CampaignController@index')->name('campaign.index');
        });
    });
});
