<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB;

class DigimonsController extends Controller
{
    public function DigimonsStore() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimonCount = $collection->count();
        $page = request("pg") == 0 ? 1 : request("pg");
        $digimons = $collection->find([], ["limit" => 12, "skip" => ($page-1) * 12]);  
        return view('Digimons.Index', ['digimons' => $digimons, 'digimonCount' => $digimonCount]);
    }

    //User

    public function AddComment() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $comment = [
            "user_id" => request('userid'),
            "comment" => request('comment'),
            "date" => date("Y-m-d H:i:s")            ];
        $digimon= $collection->findOne([ "_id" => new MongoDB\BSON\ObjectId(request('digimonid')) ]);
        $Comments = $digimon->Comments;
        if (count($Comments) == 0 || $Comments == null || empty($Comments)) {
            $Comments = [$comment];
        } else {
            $Comments = [$comment, ...$Comments];
        }
        $updateOneResult = $collection->updateOne([
            "_id" => new MongoDB\BSON\ObjectId(request('digimonid'))
        ],[
            '$set' => [ 'Comments' => $Comments ]
        ]);

        return redirect("/digimons/".request('digimonid'));
    }

    public function Details($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimon = $collection->findOne(["_id" => new MongoDB\BSON\ObjectId($id)]);
        return view('Digimons.Details', [ "digimon" => $digimon]);
    }

    //ADMIN

    public function Index() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimons = $collection->find();  
        return view('Admin.Digimons.Index', ['digimons' => $digimons]);
    }

    public function Create() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimon = $collection->find();
        return view('Admin.Digimons.Create', [ "digimons" => $digimon ]);
    }

    public function Store() {
        $digimon = [
            "Digimon" => request("Digimon"),
            "Stage" => request("Stage"),
            "Type" => request("Type"),
            "Attribute" => request("Attribute"),
            "Rating" => [],
            "Comments" => []
        ];
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $insertOneResult = $collection->insertOne($digimon);
        return redirect("/admin/digimons");
    }

    public function Edit($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimon = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectID($id) ]);
        return view('Admin.Digimons.Edit', [ "digimon" => $digimon ]);
    }    
    
    public function Update(){
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimon = [
            "Digimon" => request("Digimon"),
            "Stage" => request("Stage"),
            "Type" => request("Type"),
            "Attribute" => request("Attribute"),
            "Rating" => [],
            "Comments" => []
        ];
        $updateOneResult = $collection->updateOne([
            "_id" => new \MongoDB\BSON\ObjectId(request("digimonid"))
        ], [
            '$set' => $digimon
        ]);
        return redirect('/admin/digimons/');
    }

    public function Delete($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimon = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectID($id) ]);
        return view('Admin.Digimons.Delete', [ "digimon" => $digimon ]);
    }
    
    public function Remove(){
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $deleteOneResult = $collection->deleteOne([
            "_id" => new \MongoDB\BSON\ObjectId(request("digimonid"))
        ]);
        return redirect('/admin/digimons/');
    }

    public function Show($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Digimons;
        $digimon = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectID($id) ]);
        return view('Admin.Digimons.Details', [ "digimon" => $digimon ]);
    }

}