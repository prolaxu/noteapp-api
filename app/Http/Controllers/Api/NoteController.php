<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->sendResponse(Note::limit(10)->where('user_id',auth('api')->user()->id)->get(),"List of Notes.");
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
            $Note=new Note();
            $request->title ? $Note->title = $request->title :$err[$c++]="Title is empty";
            $request->content ? $Note->content = $request->content :$err[$c++]="Content is empty";
            $Note->user_id = auth('api')->user()->id;
            if($err==[]){
                $Note->save();
                return $this->sendResponse( $this->process($Note), 'Note record was added successfully.');
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
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show($note)
    {
        try {
            $Note=Note::where('user_id',auth('api')->user()->id)->findOrFail($note);
            return $this->sendResponse($this->process($Note), 'Note fetched successfully.'); 
        } catch ( \Throwable $t) {
            return $this->sendError("Failed to Fetch Resource.");
        }
    }

  
    public function update(Request $request,$note)
    {
        try {
            $Note=Note::where('user_id',auth('api')->user()->id)->findOrFail($note);
            $request->title ? $Note->title = $request->title :null;
            $request->content ? $Note->content = $request->content :null;
            $Note->user_id = auth('api')->user()->id;
            
            $Note->save();
            return $this->sendResponse( $this->process($Note), 'Note record was added successfully.');
        } catch (\Throwable $t) {
             return $this->sendError("Failed to Update Resource.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy($note)
    {
        try {
            $Note=Note::where('user_id',auth('api')->user()->id)->findOrFail($note);
            if($Note->delete()) {
                return $this->sendResponse(
                    $Note
                ,'Note id '.$Note->id.' Was Deleted successfully.'); 
            }
        } catch ( \Throwable $t) {
            return $this->sendError("Failed to delete note.".$t);
        }
    }
    public function process($note){
        $item=[
            "id"=> $note->id,
            "title"=> $note->title,
            "content"=> $note->content,
            "user_id"=> $note->user_id,
            "created_at"=> $note->created_at,
            "updated_at"=> $note->updated_at
        ];
        return $item;
    }
}
