<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditPosition;
use Illuminate\Http\Request;
use App\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{
    public function index() {
        return view('positions.list');
    }

    public function list() {
        $positions = Position::getAll();

        return datatables()->of($positions)
            ->editColumn('updated_at', function ($position) {
                return $position->updated_at->format('d.m.y');
            })
            ->addColumn('action', function($row){
                $btn = '<a style="padding:5px; font-size: 20px;" href="/admin/positions/editor/' . $row->id . '"><i class="fas fa-pencil-alt"></i></a>';
                $btn = $btn.' <a class="remove" data-toggle="modal" data-target="#delete" data-position_id="' . $row->id . '" data-position_name="' . $row->name . '" style="padding:5px; font-size: 20px;"><i class="fas fa-trash-alt"></i></a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
    }



    public function editor($id = 0) {
        if ($id === 0) {
            return $this->adding();
        } else {
            return $this->editing($id);
        }
    }

    public function adding() {
        return view('positions.editor', [
            'position' => false
        ]);
    }

    public function editing($id) {
        $position = Position::getOne($id);

        return view('positions.editor', [
            'position' => $position
        ]);
    }

    public function edit(EditPosition $request) {
        if($request->id == 0) {
            return $this->add($request);
        } else {
            return $this->update($request);
        }
    }







    public function add($request) {
        $fields = $request->all();
        unset($fields['_token']);
        $fields['admin_created_id'] = Auth::user()->id;
        $fields['admin_updated_id'] = Auth::user()->id;

        $position = new Position($fields);
        $position->save();

        return redirect('/admin/positions');
    }


    public function update($request) {
        $fields = $request->all();
        unset($fields['_token']);
        $fields['admin_updated_id'] = Auth::user()->id;

        Position::edit($fields['id'], $fields);

        return redirect('/admin/positions');
    }






    public function remove(Request $request) {
        $position = Position::find($request->position_id);
        $position->delete();

        return back();
    }






}
