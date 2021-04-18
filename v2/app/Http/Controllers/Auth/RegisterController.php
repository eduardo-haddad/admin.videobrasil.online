<?php

namespace App\Http\Controllers\Auth;

use DB;
use Hash;
use App\User;
use App\Client\Client;
use App\Client\ClientAccess;
use App\Http\Requests\StoreUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Notification;
use App\Notifications\NewUserClient;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'householder', 'can:manage,App\User']);
    }

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/users';

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        DB::beginTransaction();

        try{
            $user = User::create($data);

            if(isset($data['roles']) && count($data['roles']) > 0){
                $user->roles()->sync($data['roles']);
                $user->save();
            }

            if($user->hasRole('broker-lp') && isset($data['by_client'])){
                // Eloquent doesn't offer a way to attach many models
                // when on a One-to-Many relationship. Using QueryBuilder instead.
                Client::whereNull('admin_user_id')
                      ->whereIn('user_id', $data['by_client'])
                      ->update(['admin_user_id' => $user->id]);
            }

            if($user->hasRole('client-tracking') && isset($data['by_client'])){
                foreach($data['by_client'] as $client) {
                    $access = new ClientAccess();
                    $access->user()->associate($user);
                    $access->client_id = $client;
                    $access->save();
                }
                Notification::send($user, new NewUserClient($data));
            }

            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            report($e);
        }

        return $user;
    }

    /**
     * Overwritten from RegistersUsers trait to disable login after registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(StoreUser $request)
    {
        $request->merge(['password' => Hash::make($request->get('password'))]);
        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath())->with('success', 'Usuário registrado com sucesso!');
    }
}
