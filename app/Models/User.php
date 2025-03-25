<?php

namespace App\Models;

// use App\Casts\DatetimeWithTimezoneGetOnly;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'type',
        'password',
        'tenant_id',
        'email_verified_at',
        'timezone',
        'cliente_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token'
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
            // 'created_at' => DatetimeWithTimezoneGetOnly::class,
            // 'updated_at' => DatetimeWithTimezoneGetOnly::class,
            // 'deleted_at' => DatetimeWithTimezoneGetOnly::class,
        ];
    }

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];
    
    const USER = 0;
    const CLIENT = 1;
    const ADMIN = 2;

    static public $list_type_user = [
        ['type' => self::USER, 'label' => 'Usuário'],
        ['type' => self::CLIENT, 'label' => 'Operador'],
        ['type' => self::ADMIN, 'label' => 'Admin'],
    ];
    
    public function getTypeUser(): string
    {
        return match ($this->type) {
            self::USER  => "Usuário",
            self::CLIENT => "Operador",
            self::ADMIN => "Admin"
        };
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }

    public function scopeSortBy($query, $field, $direction = 'asc')
    {
        return $query->orderBy($field, $direction);
    }

    public function isAdmin(): bool
    {
        return $this->type == self::ADMIN;
    }

    public function isClient(): bool
    {
        return $this->type == self::CLIENT;
    }

    public function isUser(): bool
    {
        return $this->type == self::USER;
    }

    public function checkPassword($value): bool
    {
        if(strlen($this->password) == 32 && $this->password == md5($value) || password_verify($value, $this->password)) {
            return true;
		}

        return false;
    }

    public function vendedor()
    {
        return $this->hasOne(Vendedores::class, 'user_id', 'id')->withTrashed();
    }
}