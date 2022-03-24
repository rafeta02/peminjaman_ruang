<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Aacotroneo\Saml2\Events\Saml2LogoutEvent;
use App\Models\User;
use Auth;
use Session;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Event::listen(Saml2LoginEvent::class, function(Saml2LoginEvent $event) {
            $user = $event->getSaml2User();
            $attributes = $user->getAttributes();

            $ssoUser = User::firstOrNew([
                'nip' => $attributes['nip'][0],
            ]);

            $ssoUser->username = $attributes['username'][0];
            $ssoUser->email = $attributes['email'][0];
            $ssoUser->nama = $attributes['nama'][0];
            $ssoUser->name = $attributes['nama'][0];
            $ssoUser->no_hp = $attributes['no_hp'][0];
            $ssoUser->id_simpeg = $attributes['id_simpeg'][0];
            $ssoUser->no_identitas = $attributes['identity_numbers'][0];
            $ssoUser->alamat = $attributes['alamat'][0];
            // $ssoUser->jwt_token = $attributes['jwt_token'][0];
            // $ssoUser->foto_url = $attributes['foto_url'][0];
            $ssoUser->password = bcrypt($attributes['email'][0]);
            $ssoUser->email_verified_at = date('Y-m-d H:i:s');

            $ssoUser->save();

            $userA = User::where('id_simpeg', $attributes['id_simpeg'][0])->where('email', $attributes['email'][0])->where('nip', $attributes['nip'][0])->first();

            if (!$userA->roles()->exists()) {
                $userA->roles()->sync(3);
            }

            Auth::login($userA);
        });

        Event::listen(Saml2LogoutEvent::class, function(Saml2LogoutEvent $event) {
            Auth::logout();
            Session::save();
        });
    }
}
