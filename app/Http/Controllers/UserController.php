<?php

namespace App\Http\Controllers;


use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddRequest;
use App\Http\Requests\UserEditRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Exception;
use Psy\Util\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Регистрация пользователя  login , email , password , role
     *
     * @param UserAddRequest $request UserAddRequest
     *
     * @return mixed
     *
     * @Rest\Post("/registration")
     */
    public function registerUser(UserAddRequest $request)
    {
        $roles = Role::where('role_name', '=', $request->role)->get()->first()->id;
        $request->role_id = $roles;
        if($request->role == 'Посредник'){
            $request->role_id = 'asdasd';
        }
        $data = $request->validated();

        $user = User::create($this->generateArrayRequest($request));

        $route = $request->route;
        $link = $route . '?verification_code=' . $user->verefi_code;
        $this->messageUser($request, $link, $user->verefi_code);

        return $this->sendResponse($user, 'Created', 201);
    }

    /**
     * Подтверждение почты пользователем
     *
     * @param Request $request Request
     *
     * @return boolean
     *
     * @Rest\Post("/verification")
     */
    public function verificationUser(Request $request)
    {
        $res = $this->verificationUserCheck(User::where('verefi_code', '=', $request->verification_code));

        return response()->json($res, 200);
    }

    /**
     * Проверка уникальности почты и логина
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @Rest\Post("/unique")
     */
    public function checkUnique(Request $request)
    {
        $data = [
            'login' => $this->checkUniqueLoginUser($request->login),
            'email' => $this->checkUniqueEmailUser($request->email),
        ];

        return response()->json($data, 200);
    }

    /**
     * Вход пользвателя через login и password или email password
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @Rest\Post("/login")
     */
    public function loginUser(Request $request)
    {

        if ((Auth::attempt(['login' => request('username'), 'password' => request('password')])) ||
            (Auth::attempt(['email' => request('username'), 'password' => request('password')]))) {
            $user = Auth::user();
            $token = $user->generateToken();
            $user->remember_token = $token;

            $roles = Role::where('id', '=', $user->role_id)->get()->first()->role_name;

            return response()->json(
                [
                    'userID' => $user->id,
                    'login' => $user->login,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'first_name' => $user->first_name,
                    'role' => $roles,
                    'token' => $token,


                ],
                200
            );
        } else {
            return response()->json("Неправильное имя пользователя/email или пароль", 401);
        }
    }

    /**
     * Выход пользвателя ( logout )
     *
     * @param Request $request Request
     *
     * @return void
     *
     * @Rest\Get("/user-logout")
     */
    public function logoutUser(Request $request)
    {
        $user = Auth::user();
        $user->removeToken();
    }
    /**
     * Отправка сообщения почту при регистрации
     *
     * @param Request $request
     *
     * @return boolean
     *
     * @Rest\Post("/send")
     */
    public function toSendMessageToEmail(Request $request)
    {
        if ($user = User::where(['email' => $request->email])->first()) {
            $user = User::where(['email' => $request->email])->first();
            $user->toSendEmailLink($user, $request->route);
            return response()->json(true, 200);
        } else {
            return response()->json(false, 401);
        }
    }
    /**
     * Отправка сообщения почту при восстановлении пароля
     *
     * @param Request $request
     *
     * @return boolean
     *
     * @Rest\Post("/password")
     */
    public function resetPassword(Request $request)
    {
        if ($user = User::where(['reset_code' => $request->reset_code])->first()) {
            $user->password = Hash::make($request->password);
            $user->reset_code = null;
            $user->save();
            Mail::send(['html' => 'password'], ['name' => $user->login],
                function ($message) use ($request, $user) {
                    $message->to($user->email, $user->email)->subject('Изменение пароля');
                    $message->from('technical.platformss@gmail.com', 'Technical Platform');

                });
            return response()->json(true, 200);

        } else {
            return response()->json(false, 401);
        }
    }
    /**
     *  Проверка на код восстановления
     * ( для того чтобы при переходе на ссылку восстановления пароля писало что она неактивна)
     *
     * @param Request $request
     *
     * @return boolean
     *
     */
    public function checkResetCode(Request $request)
    {

        if ($user = User::where(['reset_code' => $request->reset_code])->first()) {
            return response()->json(true, 200);
        } else {
            return response()->json(false, 200);
        }
    }
    /**
     *  Личный кабинет
     *
     *
     *
     * @return mixed
     *
     * @Rest\Get("/user")
     */
    public function index()
    {

        $user = User::with('role')->find(Auth::user()->id);
        return $this->sendResponse($user,'ok',200);
    }
    /**
     *  Просмотр анкеты пользователя
     *  @param Request $id
     *
     *
     * @return mixed
     *
     * @Rest\Get("/user/{id}")
     */
    public function show($id)
    {
        if(  $user = User::with('role')->find($id)){
            return $this->sendResponse($user,'ok',200);
        }  else {
            return $this->sendError('Not Found User',404,$user=['Пользователь не найден']);
        }

    }
    /**
     * Редактирование данных в личном кабинете
     *
     * @param Request $request
     *
     * @return mixed
     *
     * @Rest\Post("/user")
     */
    public function edit(UserEditRequest $request){
        $user = Auth::user();
        $data = array_merge(request()->all(),
            [
                'full_name' => $request->last_name . " " .$request->first_name . " " . $request->patronymic,
            ]);

        $user->update($data);
        $user->save();
        return $this->sendResponse($user,"OK",200);
    }

}
