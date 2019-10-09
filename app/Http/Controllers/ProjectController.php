<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Project;
use App\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return User::paginate(5);
        if(!$request->isJson())
            return response()->json(['error'=>'Format not valid'],406);
        
        return Project::paginate(5);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if(!$request->isJson())
            return response()->json(['error'=>'Format not valid'],406);
        
        $data = $request->json()->all();

        $userExists = User::where('id',$data['user_id'])->exists();

        if(!$userExists)
            return response()->json(['error'=>'Invalid Parameters'],406);

        $translations = $data['translations'];

        $dataToBeSaved = [
            'user_id' => $data['user_id'],
            'thumbnail' => $data['thumbnail'],
            'image' => $data['image'],
         ];

        foreach ($translations as $translation ) {
            $dataToBeSaved[$translation["locale"]] =[
            'title' => $translation["title"],
            'description' => $translation["description"]
            ];
        }

        $project = Project::create($dataToBeSaved);

        return response()->json($project,201);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
