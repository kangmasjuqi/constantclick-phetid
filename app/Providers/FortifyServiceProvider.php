<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse; // Make sure to import your custom LoginResponse
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify; // Import the Fortify Facade
use App\Models\User; // Import the User model
use App\Actions\Fortify\UpdateUserPassword;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Configure Fortify Views (typically for Blade-based apps, but useful placeholders)
        // For a pure API backend with a separate SPA frontend, these often aren't directly used
        // by the frontend, but Fortify still has them defined internally.
        Fortify::loginView(function () {
            return view('auth.login'); // Example Blade view
        });

        Fortify::registerView(function () {
            return view('auth.register'); // Example Blade view
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email'); // Example Blade view
        });

        Fortify::resetPasswordView(function () {
            return view('auth.reset-password'); // Example Blade view
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password'); // Example Blade view
        });

        // 2. Register your custom LoginResponse binding
        // This tells Fortify to use YOUR LoginResponse class
        // instead of its default one when a login is successful.
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        // 3. Configure User Creation (Registration)
        // This defines how new users are created when someone hits the /register endpoint.
        Fortify::createUsersUsing(User::class); // Default: Uses the User model's create method

        // Or if you need custom logic (e.g., hash password, add roles):
        /*
        Fortify::createUsersUsing(function (array $data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            ]);
        });
        */

        // 4. Configure User Authentication
        // This defines how users are authenticated (e.g., using email/password).
        // Default: Uses Laravel's built-in authentication system.
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        // 5. Configure Password Updates
        // --- CHANGE THIS LINE: ---
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        // --- Instead of:
        // Fortify::updateUserPasswordsUsing(function (User $user, string $password) {
        //     $user->forceFill([
        //         'password' => \Illuminate\Support\Facades\Hash::make($password),
        //     ])->save();
        // });
        // --- End Change ---


        // 6. Rate Limiting for Login Attempts
        // This protects your /login endpoint from brute-force attacks.
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(5)->by($email . $request->ip());
        });

        // You can add more rate limiters for other Fortify features (e.g., password reset)
        // RateLimiter::for('two-factor', function (Request $request) { ... });
    }
}