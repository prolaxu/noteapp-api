<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\NoteBook;
use App\Http\Controllers\Controller;

class NoteBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->sendResponse(NoteBook::limit(10)->where('user_id',auth('api')->user()->id)->get(),"List of NoteBooks.");
        } catch (\Throwable $th) {
            return $this->sendError("Failed to Fetch Resource.");
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $c=0;
            $err=[];
            $NoteBook=new NoteBook();
            $request->title ? $NoteBook->title = $request->title :$err[$c++]="Title is empty";
            $NoteBook->user_id = auth('api')->user()->id;
            if($err==[]){
                $NoteBook->save();
                return $this->sendResponse( $this->process($NoteBook), 'NoteBook record was added successfully.');
            }else{
                return $this->sendError($err);
            }
        } catch (\Throwable $t) {
             return $this->sendError("Failed to Create Resource. ".$t->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoteBook  $NoteBook
     * @return \Illuminate\Http\Response
     */
    public function show($NoteBook)
    {
        try {
            $NoteBook=NoteBook::where('user_id',auth('api')->user()->id)->findOrFail($NoteBook);
            return $this->sendResponse($this->process($NoteBook), 'NoteBook fetched successfully.'); 
        } catch ( \Throwable $t) {
            return $this->sendError("Failed to Fetch Resource.");
        }
    }

  
    public function update(Request $request,$NoteBook)
    {
        try {
            $NoteBook=NoteBook::where('user_id',auth('api')->user()->id)->findOrFail($NoteBook);
            $request->title ? $NoteBook->title = $request->title :null;
            $request->content ? $NoteBook->content = $request->content :null;
            $NoteBook->user_id = auth('api')->user()->id;
            
            $NoteBook->save();
            return $this->sendResponse( $this->process($NoteBook), 'NoteBook record was added successfully.');
        } catch (\Throwable $t) {
             return $this->sendError("Failed to Update Resource.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoteBook  $NoteBook
     * @return \Illuminate\Http\Response
     */
    public function destroy($NoteBook)
    {
        try {
            $NoteBook=NoteBook::where('user_id',auth('api')->user()->id)->findOrFail($NoteBook);
            if($NoteBook->delete()) {
                return $this->sendResponse(
                    $NoteBook
                ,'NoteBook id '.$NoteBook->id.' Was Deleted successfully.'); 
            }
        } catch ( \Throwable $t) {
            return $this->sendError("Failed to delete NoteBook.".$t);
        }
    }
    public function process($NoteBook){
        $item=[
            "id"=> $NoteBook->id,
            "title"=> $NoteBook->title,
            "user_id"=> $NoteBook->user_id,
            "created_at"=> $NoteBook->created_at,
            "updated_at"=> $NoteBook->updated_at
        ];
        return $item;
    }
}
