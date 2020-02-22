<?php

namespace ErpNET\Profiting\Calendar\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Traits\Uploads;

//use ErpNET\Profiting\Calendar\Http\Requests\Event as Request;
use ErpNET\Profiting\Calendar\Models\Event as Model;



class FullCalendarController extends Controller
{
    use Uploads;
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            
            $data = Model::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get(['id','title','start', 'end']);
            return Response::json($data);
        }
        return view('erpnet-profiting-calendar::calendar.fullcalendar');

    }
    
    /**
     * Show the form for viewing the specified resource.
     *
     * @return Response
     */
    public function show()
    {
        return redirect()->route('fullcalendar.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {        
        return redirect()->route('fullcalendar.index');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $insertArr = [ 'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end
        ];
        $event = Model::insert($insertArr);
        return Response::json($event);
    }
   
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Model $production
     *
     * @return Response
     */
    public function edit()
    {        
        return redirect()->route('fullcalendar.index');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $where = array('id' => $request->id);
        $updateArr = ['title' => $request->title,'start' => $request->start, 'end' => $request->end];
        $event  = Model::where($where)->update($updateArr);
        
        return Response::json($event);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function destroy(Request $request)
    {        
        $event = Model::where('id',$request->id)->delete();
        
        return Response::json($event);
    }
  
}
