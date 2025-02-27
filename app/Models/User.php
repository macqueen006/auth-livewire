<?php

namespace App\Models;

use App\Traits\HasSocialProviders;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasSocialProviders, Notifiable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasVerifiedEmail()
    {
        if (!config('settings.registration_require_email_verification')) {
            return true;
        }

        return $this->email_verified_at !== null;
    }

    public function twoFactorQrCodeSvg()
    {
        return '';
    }

    public function enableTwoFactorAuthentication()
    {
        $google2fa = app(Google2FA::class);
        $this->two_factor_secret = $google2fa->generateSecretKey();
        $this->save();
    }

    public function disableTwoFactorAuthentication()
    {
        $this->two_factor_secret = null;
        $this->save();
    }

    /**
     * Generate a QR code for 2FA.
     *
     * @return string
     */
    public function generateTwoFactorQrCodeSvg()
    {
        $google2fa = app(Google2FA::class);
        $companyName = config('app.name');
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $this->email,
            $this->two_factor_secret
        );

        return \BaconQrCode\Renderer\Image\SvgImageBackEnd::class;
    }

}
