<?php

namespace App\Http\Controllers;

//use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Challanges;
use App\Models\Clip;
use App\Models\Vote;
use Validator;
class Api extends Controller
{
    function add_challange(Request $req){


    	//////// status ////////
    	// 1 => upcoming
    	// 2 => submision state (feature)
    	// 3 => voting state (feature)
    	// 4 => completed

    	$rules = array(
    		'name' => 'required|max:100',
        	'image' => 'required|mimes:gif,jpg,png,jpeg,ico',
        	'description' => 'required',
        	'rules' => 'required',
        	'reward' => 'required',
        	'number_of_participants' => 'required|integer|between:1,100',
        	'start_date' => 'required|after:' . date('Y-m-d H:i'),
        	'end_date' => 'required|after:start_date',
        	'sponsor_name' => 'required|max:100',
        );

        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
        	return $validator->errors();
        }else{
        	$start_date  = strtotime($req->start_date);
			$date_check = date('Y-m-d', $start_date);
			if($date_check == date('Y-m-d')){
				$status = 2;
			}else{
				$status = 1;
			}

			date_default_timezone_set('Africa/Cairo');

        	$challange = new Challanges;
	    	$challange->name = $req->name;
	    	$challange->image = $req->file('image')->store('challanges');
	    	$challange->description = $req->description;
	    	$challange->rules = $req->rules;
	    	$challange->reward = $req->reward;
	    	$challange->number_of_participants = $req->number_of_participants;
	    	$challange->start_date = $req->start_date;
	    	$challange->end_date = $req->end_date;
	    	$challange->sponsor_name = $req->sponsor_name;
	    	$challange->status = $status;
	    	$result = $challange->save();
	    	if($result){
	    		return['result' => 'challange has been saved'];
	    	}
	    	else{
	    		return['result' => 'something wrong'];
	    	}
        }
    }

    function show_challange($id){
    	$challange = Challanges::find($id);
    	if($challange){
    		return['result' => $challange];
    	}
    	else{
    		return['result' => 'wrong id'];
    	}
    }

    function upcoming(){
    	$challanges = Challanges::all()
             ->where('status', '=', 1);
        if(!$challanges->isEmpty()){
    		return['result' => $challanges];
    	}
    	else{
    		return['result' => 'no upcoming challanges available'];
    	}
    }

    function feature(){
    	$challanges = Challanges::where('status', '=', 2)->orWhere('status', '=', 3)->get();
        if($challanges){
    		return['result' => $challanges];
    	}
    	else{
    		return['result' => 'no feature challanges available'];
    	}
    }

    function completed(){
    	$challanges = Challanges::all()
             ->where('status', '=', 4);
        if(!$challanges->isEmpty()){
    		return['result' => $challanges];
    	}
    	else{
    		return['result' => 'no completed challanges available'];
    	}
    }

    function add_clip(Request $req){

    	$rules = array(
    		'title' => 'required|max:100',
        	'video' => 'required|mimes:mp4,mov,ogg,qt | max:20000',
        	'user_id' => 'required',
        	'challange_id' => 'required',
        );

        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
        	return $validator->errors();
        }else{
	    	$clip = new Clip;
	    	$clip->title = $req->title;
	    	$clip->video = $req->file('video')->store('clips');
	    	$clip->user_id = $req->user_id;
	    	$clip->challange_id = $req->challange_id;
	    	$result = $clip->save();
	    	if($result){
	    		return['result' => 'clip has been saved'];
	    	}
	    	else{
	    		return['result' => 'something wrong'];
	    	}
	    }
    }

    function add_vote(Request $req){

    	$rules = array(
    		'user_id' => 'required',
        	'value' => 'required|integer|between:1,5',
        	'clip_id' => 'required',
        );

        $validator = Validator::make($req->all(), $rules);

        if($validator->fails()){
        	return $validator->errors();
        }else{
	    	$vote = new Vote;
	    	$vote->user_id = $req->user_id;
	    	$vote->value = $req->value;
	    	$vote->clip_id = $req->clip_id;
	    	$result = $vote->save();
	    	if($result){
	    		return['result' => 'vote has been saved'];
	    	}
	    	else{
	    		return['result' => 'something wrong'];
	    	}
	    }
    }

    function winners($challange_id){

    	$winners = DB::select("SELECT users.name, AVG(votes.value ) AS value, users.id FROM users INNER JOIN votes ON users.id = votes.user_id INNER JOIN clips ON votes.clip_id = clips.id INNER JOIN challanges ON clips.challange_id = challanges.id WHERE challanges.id = '$challange_id' GROUP BY users.name, users.id ORDER BY AVG(votes.value ) DESC");
    	if($winners){
    		return['result' => $winners];
    	}
    	else{
    		return['result' => 'no data found'];
    	}
    }
}
