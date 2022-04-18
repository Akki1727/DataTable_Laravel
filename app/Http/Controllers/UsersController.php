<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\user as ModelsUser;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index()
    {
        return view('users');
    }

    public function getUsers(Request $request)
    {


        // if($request->ajax()){
        //     $data = User::latest()->limit(10)->get();
        //     return DataTables::of($data)->addIndexColumn()->addColumn('action',function($row){
        //         $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a>
        //             <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
        //             return $actionBtn;
        //     })->rawColumns(['action'])->make(true);
        // }

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',

        );

        $totalData = ModelsUser::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $articles = ModelsUser::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $get_search = $request->input('search.value');
            $articles =  ModelsUser::where('id', 'LIKE', "%{$get_search}%")
                ->orWhere('name', 'LIKE', "%{$get_search}%")->orWhere('email', 'LIKE', "%{$get_search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $totalFiltered = ModelsUser::where('id', 'LIKE', "%{$get_search}%")
                ->orWhere('name', 'LIKE', "%{$get_search}%")->orWhere('email', 'LIKE', "%{$get_search}%")
                ->count();
        }

        $data = array();
        if (!empty($articles)) {
            foreach ($articles as $page) {
                $show =  route('users.show', $page->id);
                $edit =  route('users.edit', $page->id);

                $customResult['id'] = $page->id;
                $customResult['name'] = $page->name;
                $customResult['email'] = $page->email;

                $customResult['action'] = " <a href='{$show}' title='User SHOW'><span class='edit btn btn-success btn-sm'>Edit</span></a>
                                       <a href='{$edit}' title='User EDIT'><span class='delete btn btn-danger btn-sm'>Delete</span></a>";
                $data[] = $customResult;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }
}
