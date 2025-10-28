<?php

namespace Modules\Security\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
//use Illuminate\Routing\Controller;
use App;
use Config;
use App\Http\Controllers\Controller;
use Modules\Security\Entities\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Encryptor;
use File;
use Response;
use App\Helpers\LockDB;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller {

    private $NAC_VENEZOLANA = 1;
    private $PAIS_VENEZUELA = 1;
    private $PROFILE_TRAMITES = 3;

    public function photo(Request $request) {
        if (\Request::isMethod('get')) {
            return view('security::users.photo');
        } else {
            dd($request->all());
        }
    }

    public function file_admin($id = null) {

        $pdfFile = storage_path($id . '.pdf');
        $FILE_PDF = new \Smalot\PdfParser\Parser();

        $documento = $FILE_PDF->parseFile($pdfFile);

        $paginas = $documento->getPages();
        //$paginas = count($paginas);
        $pages = [];
        //dd($paginas);
        foreach ($paginas as $key => $values) {
            $pages[$key] = $key + 1;
        }
        return view('security::files.see', compact('id', 'pages'));

        if (\Request::isMethod('get')) {
            $newFile = \Modules\Security\Entities\Files::get();
            return view('security::files.index', compact('newFile'));
        } else {
            if (\Request::isMethod('post')) {
                $request = \Request::all();
                $file = \Request::file('attach');
                // dd($request->img);
                try {
                    //dd($request->all());
                    $newFile = new \Modules\Security\Entities\Files();
                    $newFile->name = $request['name'];
                    $newFile->description = $request['description'];
                    $newFile->save();
                    $fileName = $newFile->id . '.' . $file->getClientOriginalExtension();

                    $file->move(storage_path('/'), $fileName);
                    return to_route('security.admin')->withSuccess(__('Ok'));
                    //$request->img->move(public_path('pagos'), $fileName);
                    //$request->img->move(storage_path('homologacion'), $fileName);
                    //Storage::disk('local')->put(public_path('pagos') . DIRECTORY_SEPARATOR . "$fileName", file_get_contents($request->img));
                    //$request->img->move(public_path('pagos'), $fileName);
                } catch (Exception $ex) {
                    
                }
            } else {
                $pdfFile = storage_path($id . '.pdf');
                $FILE_PDF = new \Smalot\PdfParser\Parser();

                $documento = $FILE_PDF->parseFile($pdfFile);

                $paginas = $documento->getPages();
                //$paginas = count($paginas);
                $pages = [];
                //dd($paginas);
                foreach ($paginas as $key => $values) {
                    $pages[$key] = $key + 1;
                }
                return view('security::files.see', compact('id', 'pages'));
            }
        }
    }

    /*
     * sudo aptitude install php8.#-imagick
     * /etc/ImageMagick-6/
     * Edit the file: Open the policy.xml
     * <policy domain="coder" rights="read|write" pattern="PDF" />
     * sudo systemctl restart apache2
     */

    public function show_pdf($pdf, $page = 0) {
        // Ruta al archivo PDF
        //$pdfFile = 'a.pdf';

        $draw = new \ImagickDraw();
        $draw->setFont(public_path('fonts/arial.ttf')); // Asegúrate de que la fuente esté disponible
        $draw->setFontSize(150);
        $draw->setFillColor(new \ImagickPixel('rgba(128, 128, 128, 0.7)')); // Color blanco con opacidad
        // Posicionar el texto
        $text = Auth::user()->full_name;
        //$draw->annotation(100, 100, $text); // Ajusta la posición según sea necesario
        $draw->setGravity(\Imagick::GRAVITY_CENTER);

        $pdfFile = storage_path($pdf . '.pdf');

        $FILE_PDF = new \Smalot\PdfParser\Parser();

        $documento = $FILE_PDF->parseFile($pdfFile);

        $paginas = $documento->getPages();

        if ($page >= count($paginas)) {
            return response()->json(["error" => "page not found"], 400);
        }

        $imagick = new \Imagick();

        # Leer la página específica del PDF
        $imagick->setResolution(200, 200); // Establecer la resolución (opcional)
        $imagick->readImage("{$pdfFile}[{$page}]");

        # Opcional: configurar el formato de salida
        $imagick->setImageFormat('png'); // Cambia 'png' a 'jpeg' o el formato que necesites
        # Guardar la imagen resultante
        $outputFile = 'pdf_img.png';

        $angle = -45; // Ángulo en grados
        $imagick->annotateImage($draw, 0, 0, $angle, $text);

        //header("Content-type: image/png");
        $IMAGE = '<img id="img-book" style="width:100%" oncontextmenu="return false;"  src="data:image/png;base64,' . base64_encode($imagick->getImageBlob()) . '">';

        # Limpiar recursos
        $imagick->clear();
        $imagick->destroy();

        //return $IMAGE;
        return view('security::files.img', compact('IMAGE'));
    }

    public function captcha($seed = null) {
        $seed = substr(bcrypt($seed == null ? rand(1, 99999) : $seed), 10, 5);
        //dd($seed);
        #create image and set background color
        $captcha = imagecreatetruecolor(200, 50);
        $color1 = rand(0, 255);
        $color2 = rand(0, 255);
        $color3 = rand(0, 255);
        $background_color = imagecolorallocate($captcha, $color1, $color2, $color3);
        imagefill($captcha, 0, 0, $background_color);

        #draw some lines
        for ($i = 0; $i < 10; $i++) {
            $color = rand(48, 96);
            imageline($captcha, rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255), imagecolorallocate($captcha, $color3, $color1, $color2));
        }
        /*
          #generate a random string of 5 characters
          $string = substr(md5(rand() * time()), 0, 5);

          #make string uppercase and replace "O" and "0" to avoid mistakes
          $string = strtoupper($string);
          $string = str_replace("O", "B", $string);
          $string = str_replace("0", "C", $string);
         */
        $string = Upper($seed);

        #save string in session "captcha" key

        /*
          session_start();
          $_SESSION["captcha"]=$string;

          //dd($string);
         */

        session()->put('SET_CAPTCHA', $string);

        #place each character in a random position
        //putenv('GDFONTPATH=' . realpath('.'));
        $font = 'fonts/arial.ttf';
        for ($i = 0; $i < 5; $i++) {
            $color = rand(0, 32);
            if (file_exists($font)) {
                $x = 4 + $i * 43 + rand(0, 6);

                $y = rand(20, 28);
                imagettftext($captcha, 15, rand(-25, 25), $x, $y, imagecolorallocate($captcha, $color, $color, $color), $font, $string[$i]);
            } else {
                $x = 5 + $i * 24 + rand(0, 6);
                $y = rand(1, 18);

                imagestring($captcha, 15, $x, $y, $string[$i], imagecolorallocate($captcha, $color, $color, $color));
            }
        }

        #applies distorsion to image
        $matrix = array(array(1, 1, 1), array(1.0, 7, 1.0), array(1, 1, 1));
        imageconvolution($captcha, $matrix, 16, 32);

        $response = Response::make(imagejpeg($captcha), 200);
        $response->header("Content-Type", "image/jpeg");
        return $response;

        #avoids catching
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);

        #return the image
        header("Content-type: image/gif");
        return imagejpeg($captcha);
    }

    public function show_avatar($img) {
        //dd(Encryptor::encrypt(10));
        $num = Encryptor::decrypt($img);

        $path = storage_path('app/public/img/avatars/' . $num . '.png');
        //dd();
        if (!File::exists($path)) {
            //return response()->json(['message' => 'Image not found.'], 404);
            $path = storage_path('app/public/img/avatars/0.png');
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    private function generateCode() {
        return str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function register(Request $request) {
        /*
          if (\Request::ajax()) {
          exit;
          }
         */
        if (\Request::isMethod('get')) {
            return view('security::users.register');
        } else {

            if (\Request::isMethod('put')) {

                $generateCode = $this->generateCode();
                $user = User::where('email', Lower($request->email))->first();
                if ($user == null) {
                    $code = \Modules\Security\Entities\Codes::where('email', strtolower($request->email))->first();
                    if ($code != null) {

                        $date = Carbon::parse($code->date);
                        $minutesSinceLastRequest = $date->diffInMinutes(Carbon::now());
                        if ($minutesSinceLastRequest > 15) {
                            $code->date = now();
                            $code->code = $generateCode;
                            $code->save();
                        } else {
                            return response()->json(["type" => "danger", "message" => __("You have an active code, you must wait 15 minutes"), "status" => "0"]);
                        }
                    } else {
                        $code = new \Modules\Security\Entities\Codes();
                        $code->code = $generateCode;
                        $code->email = Lower($request->email);
                        $code->date = now();
                        $code->save();
                    }
                    $subject = __("Registration Code");
                    $for = $code->email;

                    Mail::send('security::emails.send_code', ["code" => $generateCode], function ($email) use ($subject, $for) {
                        $email->subject($subject);
                        $email->to($for);
                        //$msj->attachData($proforma->output(), "PROFORMA_" . showCode($Proforma->id) . ".pdf");
                    });

                    return response()->json(["type" => "success", "message" => __("A code has been sent to your email"), "status" => "1"]);
                } else {
                    return response()->json(["type" => "danger", "message" => __("A User Already Exists with That Email"), "status" => "0"]);
                }
            } else {
                //dd($request->all());
                $request->validate([
                    'email' => 'required|email',
                    'code' => 'required',
                    'password' => 'required|same:re_password|min:8|max:16',
                    're_password' => 'required',
                    'captcha' => 'required',
                ]);

                if (Lower(session()->get('SET_CAPTCHA')) !== Lower($request->captcha)) {
                    return back()->withErrors(['error-message' => __('Captcha does not match')])->withInput();
                }

                $user = User::where('email', strtolower($request->email))->first();

                if ($user == null) {
                    $findCodeExists = \Modules\Security\Entities\Codes::where('email', strtolower($request->email))->first();
                    if ($findCodeExists != null) {
                        if ($findCodeExists->code == $request->code) {
                            $findCodeExists->processed = true;
                            $findCodeExists->save();

                            $Country = \Modules\Security\Entities\Countries::where('iso2', 've')->first();

                            $User = new User();
                            $User->document_type_id = $this->NAC_VENEZOLANA;
                            $User->document = "";
                            $User->full_name = "";
                            $User->email = $request->email;
                            $User->username = "";
                            $User->password = Hash::make($request->password);
                            $User->phone = "";
                            $User->country_id = $this->PAIS_VENEZUELA;
                            $User->profile_id = $this->PROFILE_TRAMITES;

                            $User->active = true;
                            $User->user_id = 1;
                            $User->register_date = now();
                            $User->ip = $request->ip();
                            $User->save();

                            return to_route('login')->withSuccess(__('Successfully Created User'));
                        } else {
                            return back()->withErrors(['error-message' => __('Your verification code is wrong')]);
                        }
                    } else {
                        return back()->withErrors(['error-message' => __('You do not have a verification code')]);
                    }
                } else {
                    return back()->withErrors(['error-message' => __('A User Already Exists with That Email')]);
                }
            }
        }
    }

    public function recovery(Request $request, $token = null) {
        /*
          if (\Request::ajax()) {
          exit;
          }
         */
        if (\Request::isMethod('get')) {
            if ($token == null) {
                return view('security::users.recovery');
            } else {

                $User = User::where('token', $token)->first();
                if ($User != null) {
                    if ($User->change_password == true) {
                        $date = Carbon::parse($User->date_change_password);
                        $minutesSinceLastRequest = $date->diffInMinutes(Carbon::now());
                        if ($minutesSinceLastRequest < 5) {
                            session()->put('SET_ID_ALTER_PASSWORD', $User->crypt_id);
                            return view('security::users.recovery_password', compact('User'));
                        } else {
                            User::whereId($User->id)->update([
                                'change_password' => false,
                                'token' => '',
                                'date_change_password' => null,
                            ]);
                            return to_route('login')->withErrors(['error-message' => __('Security Token Expired')]);
                        }
                    } else {
                        User::whereId($User->id)->update([
                            'change_password' => false,
                            'token' => '',
                            'date_change_password' => null,
                        ]);
                    }
                }
                return to_route('login');
                //
            }
        } else {
            if (\Request::isMethod('post')) {
                //dd($request->all());
                $request->validate([
                    'email' => 'required|email',
                    'captcha' => 'required',
                ]);
                if (Lower(session()->get('SET_CAPTCHA')) !== Lower($request->captcha)) {
                    return back()->withErrors(['error-message' => __('Captcha does not match')])->withInput();
                }

                $user = User::where('email', strtolower($request->email))->first();

                if ($user != null) {
                    $code = Encryptor::encrypt($this->generateCode());
                    $subject = __("Recover Password");
                    $for = $request->email;

                    try {
                        Mail::send('security::emails.recovery_password', ["code" => $code, "user" => $user->toArray()], function ($email) use ($subject, $for) {
                            $email->subject($subject);
                            $email->to($for);
                            //$msj->attachData($proforma->output(), "PROFORMA_" . showCode($Proforma->id) . ".pdf");
                        });
                    } catch (\Exception $ex) {

                        return back()->withErrors(['error-message' => __('There is a problem with your email, please check.')])->withInput();
                    }
                    User::whereId($user->id)->update([
                        'change_password' => true,
                        'token' => $code,
                        'date_change_password' => now(),
                    ]);

                    return to_route('login')->withSuccess(__('An email has been sent to you with specifications for updating your password.'));
                } else {
                    return to_route('login');
                }
            } else {
                $request->validate([
                    'password' => 'required|same:re_password|min:8|max:16',
                    're_password' => 'required',
                    'captcha' => 'required',
                ]);

                if (Lower(session()->get('SET_CAPTCHA')) !== Lower($request->captcha)) {
                    return back()->withErrors(['error-message' => __('Captcha does not match')])->withInput();
                }
                if (session()->get('SET_ID_ALTER_PASSWORD') != null) {

                    $id = Encryptor::decrypt(session()->get('SET_ID_ALTER_PASSWORD'));
                    $User = User::find($id);

                    if (Hash::check($request->password, $User->password)) {
                        return back()->withErrors(['error-message' => __('New Password cannot be same as your current password.')])->withInput();
                    }

                    User::whereId($User->id)->update([
                        'change_password' => false,
                        'token' => '',
                        'date_change_password' => null,
                        'password' => Hash::make($request->password)
                    ]);

                    return to_route('login')->withSuccess(__('User Updated Successfully'));
                } else {
                    return to_route('login')->withSuccess(__('Security Token Expired'));
                }
            }
        }
    }

    public function login(Request $request) {

        if (Auth::check() == true) {
            session()->flush();
            Auth::logout();
        }

        if (\Request::ajax()) {
            exit;
        }

        if (\Request::isMethod('get')) {
            //Config::set('app.javascripts', ['5', '6']);
            //dd(config('app.javascripts'))
            //return view('login');
            //return view('welcome');
            return view('security::users.login');
        } else {

            $request->validate([
                'username' => 'required',
                'password' => 'required',
                'captcha' => 'required',
            ]);
            if (Lower(session()->get('SET_CAPTCHA')) !== Lower($request->captcha)) {
                return back()->withErrors(['error-message' => __('Captcha does not match')])->withInput();
            }

            $type = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $user = User::where($type, strtolower($request->username))
                    //->orWhere('email', strtolower($request->username))
                    ->first();

            //dd($user);
            if ($user == null) {
                return back()->withErrors(['error-message' => __('Wrong Data')]);
            } else {
                if ($user->active == true) {
                    $userdata = array(
                        $type => strtolower($request->username),
                        'password' => $request->password
                    );
                    if (Auth::attempt($userdata)) {

                        User::whereId(Auth::user()->id)->update([
                            'change_password' => false,
                            'token' => '',
                            'date_change_password' => null,
                        ]);

                        if (Auth::user()->getModules()->count() == 1) {
                            session()->put('MODULE', Auth::user()->getModules()[0]->id);
                        }

                        return to_route('home');
                    } else {
                        return back()->withErrors(['error-message' => __('Wrong Data')])->withInput();
                    }
                } else {
                    return back()->withErrors(['error-message' => __('User blocked')])->withInput();
                }
            }
        }
    }

    public function home() {
        //dd(Auth::user()->getMenu());
        return view('security::users.home');
    }

    public function set_module($id) {


        session()->put('MODULE', Encryptor::decrypt($id));
        return to_route('home');
    }

    public function logout() {
        session()->flush();
        Auth::logout();

        return to_route('login');
    }

    public function update_profile(Request $request) {

        if (\Request::isMethod('get')) {

            $typeDoc = \Modules\Security\Entities\DocumentType::get()->pluck('code', 'crypt_id');
            return view('security::users.update_profile', compact('typeDoc'));
        } else {
            $option = LockDB::crazy($request->option);
            //dd($option);
            switch ($option) {
                case 'photo':
                    $request->validate([
                        'set_new_photo' => 'required'
                    ]);

                    $imagenCodificada = $request->set_new_photo;

                    list($type, $data) = explode(';', $imagenCodificada);
                    list(, $imagenCodificada) = explode(',', $imagenCodificada);
                    $imagenCodificada = base64_decode($imagenCodificada);

                    $fileName = Auth::user()->id . '.png';

                    $file_fin = "app" . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "avatars" . DIRECTORY_SEPARATOR . $fileName;

                    if (is_file(storage_path($file_fin))) {
                        unlink(storage_path($file_fin));
                    }

                    $fp = fopen(storage_path($file_fin), 'x');
                    fwrite($fp, $imagenCodificada);
                    fclose($fp);

                    return to_route('update_profile')->withSuccess(__('Photo Updated Correctly'));

                    break;
                case 'profile':

                    $request->validate([
                        'type_document' => 'required',
                        'document' => 'required',
                        'full_name' => 'required',
                        'phone' => 'required',
                        'country_iso2' => 'required'
                    ]);

                    /*
                     * Verificar que no este repetido la cedula
                     */
                    $Is = User::where('document_type_id', Encryptor::decrypt($request->type_document))
                            ->where('document', $request->document)
                            ->where('id', '!=', Auth::user()->id)
                            ->exists()

                    ;
                    if ($Is == true) {
                        return to_route('update_profile')->withErrors(['error-message' => __('It is already registered in the Document Number')]);
                    }

                    $Country = \Modules\Security\Entities\Countries::where('iso2', $request->country_iso2)->first();

                    $Usuario = User::find(Auth::user()->id);
                    $Usuario->document_type_id = Encryptor::decrypt($request->type_document);
                    $Usuario->document = $request->document;
                    $Usuario->full_name = $request->full_name;
                    $Usuario->phone = $request->phone;
                    $Usuario->country_id = $Country->id;

                    $Usuario->user_id = Auth::user()->id;
                    $Usuario->ip = $request->ip();

                    $Usuario->save();

                    return to_route('update_profile')->withSuccess(__('Correctly Updated Data'));

                    break;
                case 'credentials':
                    $request->validate([
                        'current_password' => 'required',
                        'new_password' => 'required|same:re_password|min:8|max:16',
                        're_password' => 'required',
                    ]);

                    if (!Hash::check($request->current_password, Auth::user()->password)) {
                        return to_route('update_profile')->withErrors(['error-message' => __("Old Password Doesn't match!")]);
                    }
                    if (Hash::check($request->new_password, Auth::user()->password)) {
                        return to_route('update_profile')->withErrors(['error-message' => __("New Password cannot be same as your current password.")]);
                    }




                    User::whereId(auth()->user()->id)->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                    return to_route('update_profile')->withSuccess(__('Password changed successfully!'));
                    //dd($request->all());
                    break;
                default:
                    return to_route('update_profile');
                    break;
            }
            return view('security::users.update_profile');
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index() {
        return view('security::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create() {
        return view('security::create');
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
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id) {
        //
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
