<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Division;
use App\Models\JobRole;
use App\Models\Role;

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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'student_id' => ['required', 'string', 'max:100'],
            'campus' => ['required', 'string', 'max:255'],
            'division_id' => ['required', 'exists:divisions,id'],
            'job_role_id' => ['required', 'exists:job_roles,id'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $participantRole = Role::where('name', 'peserta_magang')->first();

        return User::create([
            'name' => $data['full_name'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $participantRole ? $participantRole->id : 2,
            'student_id' => $data['student_id'] ?? null,
            'campus' => $data['campus'] ?? null,
            'division_id' => $data['division_id'] ?? null,
            'job_role_id' => $data['job_role_id'] ?? null,
            'is_approved' => false,
        ]);
    }

    public function showRegistrationForm()
    {
        $divisions = Division::orderBy('name')->get();
        $jobRoles = JobRole::orderBy('name')->get();

        return view('auth.register', compact('divisions', 'jobRoles'));
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        return redirect()->route('login')
            ->with('status', 'Pendaftaran berhasil. Menunggu approval admin sebelum bisa login.');
    }
}
