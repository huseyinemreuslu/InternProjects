<?php

namespace App\Http\Controllers;

use App\Models\Words;
use Illuminate\Http\Request;
use App\Models\Types;
use App\Models\Languages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
class WordsController extends Controller
{
    private $errors = [
        ["id"=>0,"status"=>"The same word or phrase is already saved"],
        ["id"=>1,"status"=>"Invalid Type"],
        ["id"=>2,"status"=>"You must enter at least one meaning"],
        ["id"=>3,"status"=>"Default value cannot be left blank"],
    ];
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('query');
        $lang = $request->get('lang');
        $type = $request->get('type');
        $resulted = [];
        $parents = [];
        if($lang == 1){
            $parents = Words::where('lang_id',$lang)->where('type',$type)->where('verb','like',"%{$search}%")->with('children')->paginate(20);
            return $parents;
        }
        else {
            $children =  Words::where('lang_id',$lang)->where('type',$type)->where('verb','like',"%{$search}%")->with('parent')->paginate(20);
            foreach ($children as $key) {
                array_push($resulted, $key['parent']);
            }
            return ['data'=>$resulted];
        }
    }

    public function validation($request,$processId){
        
        $parameters = $request->all();
        $requestLength=count($parameters);
        if($requestLength === 0){
            return $this->errors;
        }
        $parent = $parameters[0];
        unset($parameters[0]);

        /* CONTROL PARENT */
        if($parent['verb'] == '' ){
            return $this->errors[3];
        }
        $word =  trim(Str::lower($parent['verb']));
        // to ADD
        if($processId != 0) {
            $dbCheck = Words::whereRaw('LOWER(`verb`) LIKE ?', [ $word] )->where('parent_id', 0)->first();
            if($dbCheck){
                return $this->errors[0];
            }
        }
        // to UPDATE
        $parentControl = 0;
        if($processId == 0 && $parent['id'] != -1){
            $dbParent = Words::findOrFail($parent['id']);
            if(trim(Str::lower($dbParent['verb'])) === trim(Str::lower($parent['verb']))) {
                $dbParent->verb = trim($parent['verb']);
                $dbParent->save();
                $parentControl = 2;
            } else {
                $dbCheck = Words::whereRaw('LOWER(`verb`) LIKE ?', [ $word] )->where('parent_id', 0)->first();
                if($dbCheck){
                    return $this->errors[0];
                }
            }
        }

        /* CHILDREN CONTROL */
        $emptyChildren = [];
        foreach ($parameters as $child) {
            if($child['verb'] != '') {
                array_push($emptyChildren,$child);
            }
        }
        
        if(count($emptyChildren) == 0) {
            return $this->errors[2]; 
        }
        /* END OF CHILDREN CONTROL */


        if($parentControl == 2){
            return 2;
        }

        return 1;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parameters = $request->all();
        $parent = $parameters[0];
        $children = [];
        $parentRecord = null;
        unset($parameters[0]);

        if($this->validation($request,1) === 1) {
            foreach($parameters as $rq){
                if($rq['verb'] != ''){
                    if(!$parentRecord){
                        $parentRecord = Words::create([
                            'verb' => $parent['verb'],
                            'lang_id' => $parent['lang_id'],
                            'parent_id' => 0,
                            'type' => $parent['type']
                        ]);
                    }
                    $children[] = Words::create([
                        'verb' => $rq['verb'],
                        'lang_id' => $rq['lang_id'],
                        'parent_id' => $parentRecord->id,
                        'type' => $rq['type']
                    ]);
                }
            }
        } else {
            return $this->validation($request,1);
        }


        return ["id"=>200,$parentRecord,"children"=>$children];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Words  $words
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Words  $words
     * @return \Illuminate\Http\Response
     */
    public function edit(Words $words)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $parameters = $request->all();
        $parent = $parameters[0];
        $children = [];
        unset($parameters[0]);
        if($this->validation($request,0) === 1 || $this->validation($request,0) === 2) {
            $parentRecord = null;
            foreach($parameters as $rq){
                if($rq['verb'] != null){
                    if(!$parentRecord && !$this->validation($request,0) === 2){
                        $parentRecord = Words::where('id',$parent['id'])->update([
                            'verb' => $parent['verb'],
                        ]);
                    }
                    if(isset($rq['id'])){
                        if($rq['id'] == -1){
                            Words::where('id',$rq['id'])->update([
                                'verb' => $rq['verb'],
                            ]);
                        }
                        array_push($children,$rq);
                    } else {
                        $nC = Words::create([
                            'verb' => $rq['verb'],
                            'lang_id' => $rq['lang_id'],
                            'parent_id' => $parent['id'],
                            'type' => $rq['type']
                        ]);
                        array_push($children,$nC);
                    }
                }
                else {
                    if(isset($rq['id']) && $rq['id'] != -1 ){
                        Words::where('id',$rq['id'])->delete();
                    }
                }
            }
            if(!count($children))  {
                return $this->errors[2];
            }
            return ["id"=>201,$parent,"children"=>$children];
        } else {
            return $this->validation($request,0);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Words  $words
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Words::where('id',$request['id'])->delete();
        if($request['children'])
        {
            foreach ($request['children'] as $key) {
                Words::where('id',$key['id'])->delete();
            }
        }
    }
}
