<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Webpatser\Countries\Countries;

use Illuminate\Support\Facades\LOG;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $countryModel = new Countries();
        $countries = $countryModel->getListForSelect();

        return view('auth.register',[
            'countries'=>$countries
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        // need to adapt country code to be string of exactly 3 characters
        // because on client side is integer so if code is 8 need to be "008"

        $code=$request->country;

        if(strlen($code)==1) $code="00".$code;
        elseif(strlen($code)==2) $code="0".$code;

        // updating request parameter
        $request->merge([
            'country' => $code,
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'country' => ['required', 'string', 'max:3','min:3',Rule::exists('countries','country_code')],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country' => $request->country,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
