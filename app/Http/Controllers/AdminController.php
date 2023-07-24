<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\Plan;
use App\Models\Tag;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Nette\Utils\Type;

class AdminController extends Controller
{
    public function index()
    {
        session()->forget('error');

        if (Auth::guard("admin")->check()) {
            return view('admin.index');
        } elseif (Auth::guard("web")->check()) {
            return redirect('/');
        } else {
            return redirect('/login');
        }
    }

    public static function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $admin = Admin::where('email', $credentials['email'])->first();
        $user = $admin->user;

        if (Hash::check($credentials['password'], $admin->password)) {
            $user->api_token = Str::random(60);
            $user->save();

            session(['isAdmin' => true]);


            Auth::guard('admin')->login($admin);

            return redirect('/admin');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function users(string $error = null)
    {

        session()->forget('error');
        if ($error) {
            session()->put('error', $error);
        }

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $users = User::with('subscription.plan:id,name')->get();
            $plans = Plan::all();
            return view('admin.users', compact('users', 'plans'));
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.users');
        }
    }

    public function createUser(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $validator = Validator::make($request->all(), [
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'phone' => ['required', 'string',],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessages = "";

                foreach ($errors->all() as $message) {
                    $errorMessages .= "- " . $message . "\n";
                }

                return $this->users($errorMessages);
            }

            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->username = $request->username;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->description = $request->description;

            $user->save();

            $subscription = new Subscription();
            $subscription->user_id = $user->id;
            $subscription->plan_id = 1;

            $subscription->save();

            if ($request->is_admin) {
                $admin = new Admin();
                $admin->user_id = $user->id;
                $admin->email = $request->admin_email;
                $admin->password = Hash::make($request->admin_password);
                $admin->is_super_admin = $this->convertToBoolean($request->is_super_admin);
                $admin->manage_admins = $this->convertToBoolean($request->manage_admins);
                $admin->manage_users = $this->convertToBoolean($request->manage_users);
                $admin->manage_providers = $this->convertToBoolean($request->manage_providers);
                $admin->manage_services = $this->convertToBoolean($request->manage_services);
                $admin->manage_plans = $this->convertToBoolean($request->manage_plans);
                $admin->save();
            }
            return redirect()->route('admin.users');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour créer un nouvel utilisateur.');
            return view('admin.users');
        }
    }

    private function convertToBoolean($value)
    {
        return $value === 'on' ? true : false;
    }

    public function deleteUser(Request $request, int $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $user = User::find($request->id);
            $user->delete();
            return redirect()->route('admin.users');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.users');
        }
    }
    public function updateUser(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $user = User::find($id);
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->username = $request->username;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->description = $request->description;

            $user->save();

            $subscription = Subscription::all()->where('user_id', $id)->first();
            $subscription->plan_id = $request->plan_id;

            $subscription->save();

//            if ($request->is_admin) {
//                $admin = new Admin();
//                $admin->user_id = $user->id;
//                $admin->email = $request->admin_email;
//                $admin->password = Hash::make($request->admin_password);
//                $admin->is_super_admin = $this->convertToBoolean($request->is_super_admin);
//                $admin->manage_admins = $this->convertToBoolean($request->manage_admins);
//                $admin->manage_users = $this->convertToBoolean($request->manage_users);
//                $admin->manage_providers = $this->convertToBoolean($request->manage_providers);
//                $admin->manage_services = $this->convertToBoolean($request->manage_services);
//                $admin->manage_plans = $this->convertToBoolean($request->manage_plans);
//                $admin->save();
//
//                return redirect()->route('admin.types');
//            } else {
//                session()->put('error', 'L\'article que vous essayez de mettre à jour n\'existe pas.');
//                return view('admin.types');
//            }
            return redirect()->route('admin.users');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour mettre à jour cet article.');
            return view('admin.users');
        }
    }
    ///////////////////////////////////////TYPES//////////////////////////////////////////
    public function types()
    {
        session()->forget('error');

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $types = ItemType::all();
            return view('admin.types', compact('types'));
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.types');
        }
    }
    public function deleteType(Request $request, int $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $ItemType = ItemType::find($request->id);
            $ItemType->delete();
            return redirect()->route('admin.types');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.types');
        }
    }


    public function createType(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $type = new ItemType();
            $type->type_name = $request->type_name;

            $type->save();

            return redirect()->route('admin.types');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour créer un nouveau type.');
            return view('admin.types');
        }
    }
    public function updateType(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $type = ItemType::find($id);
            if ($type) {
                $type->type_name = $request->type_name;
                $type->save();

                return redirect()->route('admin.types');
            } else {
                session()->put('error', 'L\'article que vous essayez de mettre à jour n\'existe pas.');
                return view('admin.types');
            }
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour mettre à jour cet article.');
            return view('admin.types');
        }
    }
    ///////////////////////////////////////TYPES//////////////////////////////////////////
    ///////////////////////////////////////TAGS//////////////////////////////////////////
    public function tags()
    {
        session()->forget('error');

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $tags = Tag::all();
            return view('admin.tags', compact('tags'));
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.tags');
        }
    }
    public function deleteTag(Request $request, int $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $tags = Tag::find($request->id);
            $tags->delete();
            return redirect()->route('admin.tags');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.tags');
        }
    }
    public function createTag(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $tags = new Tag();
            $tags->name = $request->name;

            $tags->save();

            return redirect()->route('admin.tags');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour créer un nouveau type.');
            return view('admin.tags');
        }
    }
    public function updateTag(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $tag = Tag::find($id);
            if ($tag) {
                $tag->name = $request->name;
                $tag->save();

                return redirect()->route('admin.tags');
            } else {
                session()->put('error', 'L\'article que vous essayez de mettre à jour n\'existe pas.');
                return view('admin.tags');
            }
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour mettre à jour cet article.');
            return view('admin.tags');
        }
    }
    ///////////////////////////////////////TAGS//////////////////////////////////////////
    ///////////////////////////////////////ARTICLES//////////////////////////////////////////
    public function articles()
    {
        session()->forget('error');

        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $articles = Item::all();
            $tags = Tag::all();
            $items_types= ItemType::all();
//            $items_tags = DB::select('SELECT * FROM item_tag ');


            return view('admin.Items', compact('articles', 'tags', 'items_types'));
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.Items');
        }
    }

    public function deleteArticles(Request $request, int $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_users) {
            $articles = Item::find($request->id);
            $articles->delete();
            return redirect()->route('admin.items');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return view('admin.Items');
        }
    }
    public function createArticles(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $articles = new Item();
            $articles->model_name = $request->model_name;
            $articles->selling_price = $request->selling_price;
            $articles->item_type_id = $request->itemType;
            $articles->save();

            $tagIds = $request->tag_id; // make sure this is an array of tag ids
            $articles->tags()->attach($tagIds);

            return redirect()->route('admin.items');
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour créer un nouveau type.');
            return view('admin.Items');
        }
    }
    public function updateArticles(Request $request, $id)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin->is_super_admin || $admin->manage_Services) {
            $article = Item::find($id);
            if ($article) {
                $article->model_name = $request->model_name;
                $article->selling_price = $request->selling_price;
                $article->item_type_id = $request->itemType;
                $article->save();
                $tagIds = Tag::find($request->tag_id); // make sure this is an array of tag ids
                $article->tags()->sync($tagIds);

                return redirect()->route('admin.items');
            } else {
                session()->put('error', 'L\'article que vous essayez de mettre à jour n\'existe pas.');
                return view('admin.Items');
            }
        } else {
            session()->put('error', 'Vous n\'avez pas les droits pour mettre à jour cet article.');
            return view('admin.Items');
        }
    }

    ///////////////////////////////////////ARTICLES//////////////////////////////////////////

}
