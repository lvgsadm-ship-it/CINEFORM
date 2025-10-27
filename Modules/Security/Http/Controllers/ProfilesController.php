<?php

namespace Modules\Security\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Security\Entities\Profile;
use DataTables;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Encryptor;

class ProfilesController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        //$Profiles_active = Profile::where("status", 1)->get();

        return view('security::profiles.index');
    }

    public function list(Request $request) {
        if (\Request::ajax()) {
            if ($request->search == null) {
                $Profiles = Profile::limit(100)
                        ->orderBy("id", "desc")
                        ->where('active', 'true')
                        ->get();
            } else {
                $Profiles = Profile::limit(100)
                        ->where('active', 'true')
                        ->Where('name', 'ILIKE', '%' . Upper($request->search) . '%')
                        ->orderBy("id", "desc")
                        ->get();
            }



            $data = DataTables::of($Profiles)
                    ->addIndexColumn()
                    /*
                      ->filter(function ($instance) {

                      return true;

                      if ($input != null) {
                      $instance->collection = $instance->collection->filter(function ($row) use ($input) {
                      if (Str::contains(Str::lower($row['aeronave']['full_nombre_tn']), Str::lower($input))) {
                      return true;
                      } else {
                      if (Str::contains(Str::lower($row['piloto']['full_name']), Str::lower($input))) {
                      return true;
                      } else {
                      if (Str::contains(Str::lower($row['aeropuerto']['full_nombre']), Str::lower($input))) {
                      return true;
                      } else {
                      if (Str::contains(Str::lower($row['fecha_operacion2']), Str::lower($input))) {
                      return true;
                      } else {

                      }
                      }
                      }
                      }
                      return false;
                      });
                      }


                      })
                     * 
                     */
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<div class=" text-center">';

                        if ($row->active == true) {
                            $actionBtn .= '<a  title=""  href="' . route('profiles.update', $row->crypt_id) . '"   class="btn btn-icon btn-link    btn-xs"> <span class="fa fa-edit"></span></a> ';
                            ;
                            $actionBtn .= '<a  title=""  href="' . route('profiles.permissions', $row->crypt_id) . '" class="btn btn-icon btn-link   btn-warning btn-xs"><span class="fa fa-lock"></span></a> ';
                        } else {
                            $actionBtn = __('Inactive Profile');
                        }
                        $actionBtn .= '</div>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
            //->make(true)

            ;
            return $data->toJson();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request) {
        if (!\Request::ajax()) {
            if (\Request::isMethod('get')) {
                return view('security::profiles.create');
            } else {
                $request->validate([
                    'name' => 'required',
                    'description' => 'required'
                ]);

                $Profile = new Profile();
                $Profile->name = Upper($request->name);
                $Profile->description = $request->description;

                $Profile->active = true;
                $Profile->user_id = Auth::user()->id;
                $Profile->register_date = now();
                $Profile->ip = $request->ip();
                $Profile->save();

                return to_route('profiles')->withSuccess(__('Successfully Created Profile'));
            }
        }
    }

    public function permissions($id, Request $request) {
        if (!\Request::ajax()) {
            if (\Request::isMethod('get')) {
                $profile = Profile::with('getPermissions')->find(Encryptor::decrypt($id));
                //dd($profile->toArray());
                $MODULES = \Modules\Security\Entities\Modulo::with('getMenus.getProcess')->get();
                
                return view('security::profiles.permissions', compact('id', 'profile', 'MODULES'));
            } else {
                dd($request->all());
                $request->validate([
                    'name' => 'required',
                    'description' => 'required'
                ]);
                $Profile = Profile::find(Encryptor::decrypt($id));
                $Profile->name = Upper($request->name);
                $Profile->description = $request->description;
                $Profile->active = $request->active;
                $Profile->save();

                return to_route('profiles')->withSuccess(__('Successfully Updated Profile'));
            }
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update($id, Request $request) {
        if (!\Request::ajax()) {
            if (\Request::isMethod('get')) {
                $profile = Profile::find(Encryptor::decrypt($id));
                return view('security::profiles.update', compact('id', 'profile'));
            } else {
                $request->validate([
                    'name' => 'required',
                    'description' => 'required'
                ]);
                $Profile = Profile::find(Encryptor::decrypt($id));
                $Profile->name = Upper($request->name);
                $Profile->description = $request->description;
                $Profile->active = $request->active;
                $Profile->save();

                return to_route('profiles')->withSuccess(__('Successfully Updated Profile'));
            }
        }
    }

    public function disabled($id) {
        if (!\Request::ajax()) {
            $Profile = Profile::find(Encryptor::decrypt($id));
            $Profile->active = false;
            $Profile->save();
            return to_route('profiles')->withSuccess(__('Profile Deactivated Successfully'));
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request) {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id) {
        return view('security::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id) {
        return view('security::edit');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id) {
        //
    }
}
