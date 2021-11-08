<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use App\Http\Requests\PlaceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:checkUser,task')->only([
            'update','updateDone','destroy'
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function index(Request $request)
    {
//        return Place::orderByDesc('id')->get();

        $t = $request->t;
        $b = $request->b;
        $r = $request->r;
        $l = $request->l;

//        return Place::query()->whereRaw("ST_Contains(ST_GeomFromText('Polygon((? ?,? ?,? ?,? ?,? ?))',4326),position)",[$t, $l,$t, $r,$b ,$r,$b, $l,$t, $l])->get();

//        return Place::query()->whereRaw("ST_Contains(ST_GeomFromText('Polygon((? ?,? ?,? ?,? ?,? ?))',4326),position)",[37.5, 126,38, 126,38 ,127,37.5, 127,37.5, 126])->get();

        $polygon = $t.' '.$l.','.$t.' '. $r.','.$b .' '.$r.','.$b.' '. $l.','.$t.' '. $l;
        $result= Place::query()->whereRaw("ST_Contains(ST_GeomFromText('Polygon((".$polygon."))',4326),position)")->take(10)->get();

        return $result;
//        "ST_Contains(ST_GeomFromText('Polygon((:t :l,:t :r,:b :r,:b :l,:t :l))',4326),position)",["t"=>$t,"b"=>$b,"l"=>$l,"r"=>$r]
//        dd($t,$b,$r,$l);

//        return  Place::where('user_id',Auth::id())->orderByDesc('id')->get();


    }


    /**
     * @param PlaceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PlaceRequest $request)
    {
//        dd($request->all());
        $request->merge([
            'user_id' =>Auth::id()
        ]);
        $place = Place::create($request->all()); // create 이용할려면 Model fillable 설정

        return $place
            ? response()->json($place,201)
            : response()->json([],500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show(Place $place)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Place $place)
    {
        $place->name = $request->name;

        return $place->update()
            ? response()->json($place)
            : response()->json([],500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Place $place)
    {
        return $place->delete()
            ? response()->json($place)
            : response()->json([],500);
    }


    /**
     * @param Place $place
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBookmark(Place $place, Request $request){
        $place->bookmark = $request->check;
//        abort(500);
        return $place->update() ? response()->json($place) : response()->json([],500);
    }
}