<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Task;
use DB;
use Auth;
use Session;
// use Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $search = Input::get('search');

        $order = Input::get('order');

        $lookat = Input::get('lookat');

        if ($search != NULL) {
            $tasks = DB::table('tasks')
                    ->where('user_id', Auth::user()->id)
                    ->where('description','like','%'.$search.'%')
                    ->orderBy('id', 'desc')->get();
                    // ->paginate(7); 
        }else if ($search == NULL && $order != NULL){
            $tasks = DB::table('tasks')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy($order, 'ASC')->get();
                        // ->paginate(7);            
        }else{
            $tasks = DB::table('tasks')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('id', 'desc')->get();
                        // ->paginate(7);             
        }


        $date = date('Y-m-d');

        if($lookat == "tomorrow"){

            $strdate = strtotime("+1 day", strtotime($date));

            $end_date = date('Y-m-d', $strdate);


            $tasks = DB::table('tasks')
                        ->where('user_id', Auth::user()->id)
                        ->where('due','>',$date)
                        ->where('due','<=',$end_date)
                        ->orderBy('id', 'desc')->get();
            
        }

        if ($lookat == "next7days") {
            
            $strdate = strtotime("+7 day", strtotime($date));

            $end_date = date('Y-m-d', $strdate);

            $tasks = DB::table('tasks')
                        ->where('user_id', Auth::user()->id)
                        ->where('due','>',$date)
                        ->where('due','<=',$end_date)
                        ->orderBy('id', 'desc')->get();

        }

        $fatalmessage = "Past Due for ";

        $warningmessage = "Due date for ";

        foreach ($tasks as $task) {
            if($date > $task->due){

                $fatalmessage .= 'task ' . $task->id . ' : ' . $task->description . '; '; 
            }

            if (strlen($fatalmessage)>15) {
                Session::flash('FATAL', $fatalmessage); 
            }

            if($date == $task->due){

                $warningmessage .= 'task ' . $task->id . ' : ' . $task->description . '; ';

            }

            if (strlen($warningmessage)>15) {
                Session::flash('WARNING', $warningmessage);
            }
   
        }

        return view('tasks.index')->with('userTasks',$tasks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                'description' => 'required|min:5|max:255',
                'category' => 'required',
                'duedate' => 'required',
            ]);

        $task = new Task;

        $task->description = $request->description;

        $task->category = $request->category;

        $task->user_id = $request->id;

        $task->due = $request->duedate;

        $task->save();

        Session::flash('success', 'New task has been added!');

        return redirect()->route('tasks.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

        return view('tasks.edit')->with('task',$task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
                'description' => 'required|min:5|max:255',
                'category' => 'required',
                'duedate' => 'required',
            ]);    

        $task = Task::find($id);

        $task->description = $request->description;

        $task->category = $request->category;

        $task->due = $request->duedate;

        $task->save();

        Session::flash('success','Task #' . $id . ' has been successfully updated');

        return redirect()->route('tasks.index');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);

        $task->delete();

        Session::flash('success', 'Task #' . $id . ' has been successfully deleted');

        return redirect()->route('tasks.index');
    }
}
