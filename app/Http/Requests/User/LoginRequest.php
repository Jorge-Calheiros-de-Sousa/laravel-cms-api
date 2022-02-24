<?php

namespace App\Http\Requests\User;

use App\Repositories\Contracts\UserRepositoryContract;
use Auth;
use Hash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use RateLimiter;
use Str;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "email" => "required|email",
            "password" => "required|min:8",
            "device_name" => "required|string"
        ];
    }

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        /**
         * @var UserRepositoryContract
         */
        $useRepository = app(UserRepositoryContract::class);
        $user = $useRepository->findValue("email", $this->input("email"));
        if (!$user || !Hash::check($this->input("password"), $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                "modal-message" => __('auth.failed')
            ]);
        }
        RateLimiter::clear($this->throttleKey());
        return $user;
    }

    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            "email" => __("auth.throttle", ["seconds" => $seconds, "minutes" => ceil($seconds / 60)])
        ]);
    }

    public function throttleKey()
    {
        return Str::lower($this->input("email") . "|" . $this->ip());
    }
}
