<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowRecord;

class BorrowBookRecordController extends Controller
{
  public function index()
  {
    $borrowBookRecords = BorrowRecord::with(['user', 'book'])->get();
    return response()->json($borrowBookRecords, 200);
  }
  public function addBorrowBookRecord()
  {
    $borrowBookRecord = new BorrowRecord();
    $validatedData = request()->validate([
      'book_id' => 'required|integer',
      'borrowed_at' => 'required|date',
      'returned_at' => 'nullable|date',
    ]);

    $borrowBookRecord->setData($validatedData);
    $borrowBookRecord->save();

    return response()->json(['message' => 'Borrow book record added successfully'], 201);
  }

  public function returnBookRecord($id)
  {
    $borrowBookRecord = BorrowRecord::find($id);

    if (!$borrowBookRecord) {
      return response()->json(['message' => 'Borrow book record not found'], 404);
    }

    $borrowBookRecord->returned_at = now();
    $borrowBookRecord->save();

    return response()->json(['message' => 'Book returned successfully'], 200);
  }


  public function getBorrowBookByID($id){
    $borrowBookRecord = BorrowRecord::with(['user', 'book'])->find($id);
    return response()->json($borrowBookRecord, 200);
  }

  public function deleteBorrowBookRecord($id){
    $borrowBookRecord = BorrowRecord::find($id);
    
    if (!$borrowBookRecord) {
      return response()->json(['message' => 'Borrow book record not found'], 404);
    }

    $borrowBookRecord->delete();

    return response()->json(['message' => 'Borrow book record deleted successfully'], 200);
  }
}
