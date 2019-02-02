<?php

Route::get('/kuldsuda/kuldsuda/testcontroller', 'Kuldsuda\Kuldsuda\Controllers\Acknowledgeduser@testController');
Route::post('/kuldsuda/kuldsuda/saveuseranswer', 'Kuldsuda\Kuldsuda\Controllers\Acknowledgeduser@saveUserAnswer');
Route::post('/kuldsuda/kuldsuda/saverecognition', 'Kuldsuda\Kuldsuda\Controllers\Acknowledgeduser@saveRecognition');
Route::post('/kuldsuda/kuldsuda/saveimage', 'Kuldsuda\Kuldsuda\Controllers\Acknowledgeduser@saveImage');
Route::post('/kuldsuda/kuldsuda/sendemail', 'Kuldsuda\Kuldsuda\Controllers\Acknowledgeduser@sendEmail');