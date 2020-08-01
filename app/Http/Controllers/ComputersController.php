<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB;

class ComputersController extends Controller
{
    public function ComputersStore() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computerCount = $collection->count();
        $page = request("pg") == 0 ? 1 : request("pg");
        $computers = $collection->find([], ["limit" => 12, "skip" => ($page-1) * 12]);  
        return view('Computers.Index', ['computers' => $computers, 'computerCount' => $computerCount]);
    }

    // User

    public function AddComment() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $comment = [
            "user_id" => request('userid'),
            "comment" => request('comment'),
            "date" => date("Y-m-d H:i:s")            ];
        $computer = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectId(request('computerid')) ]);
        $Comments = $computer->Comments;
        if (count($Comments) == 0 || $Comments == null || empty($Comments)) {
            $Comments = [$comment];
        } else {
            $Comments = [$comment, ...$Comments];
        }
        $updateOneResult = $collection->updateOne([
            "_id" => new MongoDB\BSON\ObjectId(request('computerid'))
        ],[
            '$set' => [ 'Comments' => $Comments ]
        ]);

        return redirect("/computers/".request('computerid'));
    }

    public function Details($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computer = $collection->findOne(["_id" => new MongoDB\BSON\ObjectId($id)]);
        return view('Computers.Details', [ "computer" => $computer]);
    }

    //ADMIN

    public function Index() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computers = $collection->find();  
        return view('Admin.computers.Index', ['computers' => $computers]);
    }

    public function Create() {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computer = $collection->find();
        return view('Admin.Computers.Create', [ "computers" => $computer ]);
    }

    public function Store() {
        $computer = [
            "Brand" => request("Brand"),
            "RAM" => request("RAM"),
            "CPU" => request("CPU"),
            "GPU" => request("GPU"),
            "Operative_system" => request("Operative_system"),
            "Rating" => [],
            "Comments" => []
        ];
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $insertOneResult = $collection->insertOne($computer);
        return redirect("/admin/computers");
    }

    public function Edit($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computer = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectID($id) ]);
        return view('Admin.Computers.Edit', [ "computer" => $computer ]);
    }    
    
    public function Update(){
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computer = [
            "Brand" => request("Brand"),
            "RAM" => request("RAM"),
            "CPU" => request("CPU"),
            "GPU" => request("GPU"),
            "Operative_system" => request("Operative_system"),
            "Rating" => [],
            "Comments" => []
        ];
        $updateOneResult = $collection->updateOne([
            "_id" => new \MongoDB\BSON\ObjectId(request("computerid"))
        ], [
            '$set' => $computer
        ]);
        return redirect('/admin/computers/');
    }

    public function Delete($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computer = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectID($id) ]);
        return view('Admin.Computers.Delete', [ "computer" => $computer ]);
    }
    
    public function Remove(){
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $deleteOneResult = $collection->deleteOne([
            "_id" => new \MongoDB\BSON\ObjectId(request("computerid"))
        ]);
        return redirect('/admin/computers/');
    }

    public function Show($id) {
        $collection = (new MongoDB\Client)->ROMANs_DB->Computers;
        $computer = $collection->findOne([ "_id" => new MongoDB\BSON\ObjectID($id) ]);
        return view('Admin.Computers.Details', [ "computer" => $computer ]);
    }

}