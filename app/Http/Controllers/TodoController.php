<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(){
        $todo_data = todo::where('is_completed', '=', 0)->take(10)->get();
        return view('todo',compact('todo_data'));
      }
     
      public function getTodo(Request $req){
         if($req->status == 1){
            $todo_data = todo::all();
         }else{
            $todo_data = todo::where('is_completed', '=', 0)->get();
   
         }
         return response()->json(["todo_data"=>$todo_data], 201);
      }
   
      public function save(Request $req){
           $validated = $req->validate([
               'todo_title' => 'required|max:255',
           ]);
   
         $todo_data = todo::where('user_id', '=', Auth::user()->id )->where('title', '=', strtolower($req->input('todo_title')))->count();
         if($todo_data < 1){
            $todo = new todo();
            $todo->user_id =  Auth::user()->id;
            $todo->title =  strtolower($req->input('todo_title'));
            $todo->is_completed = "0";
            $todo->save();
                 return response()->json(["status"=>"success"], 201);
         }else{
   
                 return response()->json(["status"=>"fail", 'message'=>"todo already exist"], 201);
         }
   
   
       }
   
      public function update(Request $req) {
         todo::where('id', $req->todo_id)->update(['is_completed' => $req->is_completed]);
         return response()->json(["status" => "success"], 201);
     }
     
      public function destroy(Request $req){
         $toda_data = todo::where('id', '=', $req->todo_id)->delete();
            return response()->json(["status"=>"success"], 201);
      }
   
      public function UserFullName(Request $req){
         $toda_data = User::where('id', '=', $req->userId)->select('name')->get();
            return response()->json(["status"=>"success","user_data"=>$toda_data], 201);
      }
   
   
      }
