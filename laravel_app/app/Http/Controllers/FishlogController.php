<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FishlogController extends Controller
{
    public static function readJson()
    {
        $path = public_path() . "/sku.json"; // ie: /var/www/laravel/app/storage/json/filename.json

        $json = json_decode(file_get_contents($path), true);

        return $json;
    }

    public function sortProduct(Request $request)
    {
        $json = self::readJson();

        array_multisort(array_map(function ($element) {
            return $element['product__name'];
        }, $json), SORT_DESC, $json);

        return response()->json(array(
            'data' => $json
        ));
    }

    public function filterInput(Request $request)
    {
        $json = self::readJson();

        $search = $request->get('search', null);
        $list_filtered = array();
        if ($search) {
            $search = strtolower($search);
            foreach ($json as $value) {
                if (strpos(strtolower($value['SKU']), $search) > -1  || strpos(strtolower($value['item_name']), $search) > -1 || strpos(strtolower($value['product__name']), $search) > -1) {
                    array_push($list_filtered, $value);
                }
            }
        } else {
            $list_filtered = $json;
        }

        return response()->json(array(
            'data' => $list_filtered
        ));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(
                array('error' => $validator->errors())
            );
        }

        $email = $request->get('email');
        $password = $request->get('password');
        $name = $request->get('name');

        $user = User::where('email', $email)->first();
        if ($user) {
            return response()->json(
                array('error' => 'Email already used')
            );
        }

        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make($password);

        if ($user->save()) {
            return response()->json(
                array('success' => 'Success Register')
            );
        } else {
            return response()->json(
                array('error' => 'Failed Save data')
            );
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(
                array('error' => $validator->errors())
            );
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(
                array('error' => 'Failed Login')
            );
        }

        if (Hash::check($password, $user->password)) {
            return response()->json(
                array('success' => 'Login Success')
            );
        } else {
            return response()->json(
                array('error' => 'Failed Login')
            );
        }
    }

    public function getUser(Request $request)
    {
        $sort_dir = $request->get('sort_dir', 'desc');
        // $start = $request->get('start', 0);
        $limit = $request->get('limit', 10);

        $page = $request->get('page', 1);

        $start = ($page - 1) * $limit;

        $users = User::orderBy('created_at', $sort_dir)->skip($start)->take($limit)->get();

        return response()->json(
            array('data' => $users)
        );
    }

    public function searchUser(Request $request)
    {
        $search = $request->get('search');

        $users = User::orderBy('created_at', 'desc');
        if ($search) {
            $users = $users->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        $users = $users->get();

        return response()->json(
            array('data' => $users)
        );
    }
}
