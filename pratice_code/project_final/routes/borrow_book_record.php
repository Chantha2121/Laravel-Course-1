<?php

use App\Http\Controllers\Api\BorrowBookRecordController;
use Illuminate\Support\Facades\Route;

Route::post('borrow_book_record', [BorrowBookRecordController::class, 'addBorrowBookRecord']);
Route::put('borrow_book_record/{id}', [BorrowBookRecordController::class, 'returnBookRecord']);
Route::get('borrow_book_record', [BorrowBookRecordController::class, 'index']);
Route::get('borrow_book_record/{id}', [BorrowBookRecordController::class, 'getBorrowBookByID']);
Route::delete('borrow_book_record/{id}', [BorrowBookRecordController::class, 'deleteBorrowBookRecord']);
