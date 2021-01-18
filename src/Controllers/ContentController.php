<?php
namespace Railroad\MusoraApi\Controllers;

use Illuminate\Routing\Controller;

class ContentController extends Controller
{
    public function getContent(\Illuminate\Http\Request $request){
       return response()->json('test');
    }
}
