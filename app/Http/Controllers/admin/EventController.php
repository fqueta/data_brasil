<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Registra eventos no sistema
     * @return bool;
     */
    static function regEvent($config=false)
    {
        //return true;
        //$ev = new EventController;
        $user = Auth::user();
        $ret =false;
        if($user){

            if(isset($config['action']) && isset($config['action'])){
                $action = isset($config['action'])?$config['action']:false;
                $tab = isset($config['tab'])?$config['tab']:false;
                $conf = isset($config['config'])?$config['config']:[];
                $conf['IP'] = Qlib::get_client_ip();
                $ret = Event::create([
                    'token'=>uniqid(),
                    'user_id'=>$user->id,
                    'action'=>$action,
                    'tab'=>$tab,
                    'config'=>Qlib::lib_array_json($conf),
                ]);
            }
        }
        return $ret;
    }
    public function listEventsUser($config = null)
    {
        $id_user = isset($config['id_user'])?$config['id_user'] : 0;
        $d = Event::where('id',$id_user)->get();
        return $d;
    }
}
