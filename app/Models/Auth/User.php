<?php

namespace NeubusSrm\Models\Auth;

use Alsofronie\Uuid\UuidModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use NeubusSrm\Events\UserWasAdded;
use NeubusSrm\Lib\Logging\NeuLoggableDetails;
use NeubusSrm\Lib\Traits\LegacySoftDeletes;
use NeubusSrm\Models\Indexing\Box;
use NeubusSrm\Models\Indexing\Part;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Models\Util\Session;


/**
 * NeubusSrm\Models\Auth\User
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $company_id
 * @property string|null $deleted_at
 * @property int|null $verification_token_id
 * @property bool $is_temp
 * @property bool $has_mfa
 * @property string|null $otp_secret
 * @property string $role
 * @property string|null $default_project_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\NeubusSrm\Models\Indexing\Box[] $boxes
 * @property-read \NeubusSrm\Models\Org\Company $company
 * @property-read \NeubusSrm\Models\Org\Company $contactFor
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \NeubusSrm\Lib\Wrappers\Collections\PartsCollection|\NeubusSrm\Models\Indexing\Part[] $parts
 * @property-read \NeubusSrm\Models\Util\Session $session
 * @property-read \NeubusSrm\Models\Auth\VerificationToken $token
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereDefaultProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereHasMfa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereOtpSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereVerificationTokenId($value)
 * @mixin \Eloquent
 * @property bool $otp_verified
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Auth\User onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereIsTemp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereOtpVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Auth\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\NeubusSrm\Models\Auth\User withoutTrashed()
 * @property bool|null $verify_mfa
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereVerifyMfa($value)
 * @property bool $is_deleted
 * @method static \Illuminate\Database\Eloquent\Builder|\NeubusSrm\Models\Auth\User whereIsDeleted($value)
 */
class User extends Authenticatable implements NeuLoggableDetails
{
    use Notifiable, LegacySoftDeletes, UuidModelTrait,
    SoftDeletes {
    LegacySoftDeletes::runSoftDelete insteadof SoftDeletes;
    LegacySoftDeletes::restore insteadof SoftDeletes;
    LegacySoftDeletes::trashed insteadof SoftDeletes;
    LegacySoftDeletes::bootSoftDeletes insteadof SoftDeletes;
}

    const DELETED_AT = 'is_deleted';

	// reserved
    const LEVEL_ADMIN = 'admin';
    const LEVEL_USER = 'user';
    // end reserved


    const ROLE_IT = 'it';
    const ROLE_CLIENT = 'client';
    const ROLE_ADMIN = 'admin';
    const ROLE_NEUBUS = 'neubus';


    protected $table = 'users';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
	    'has_mfa',
        'company_id',
        'role',
        'otp_secret',
        'verification_token_id',
        'is_temp',
        'verify_mfa'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_secret',
        'deleted_at',
        'email_verified_at',
        'verify_mfa'
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => UserWasAdded::class
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parts() {
        return $this->hasMany(Part::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
    public function contactFor() {
        return $this->hasOne(Company::class, 'company_contact', 'id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
    public function token() {
    	return $this->hasOne(VerificationToken::class, 'id', 'verification_token_id');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function boxes() {
    	return $this->hasMany(Box::class, 'created_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function session() : BelongsTo {
        return $this->belongsTo(Session::class, 'id', 'user_id');
    }

    /**
     * @param Collection $arguments
     * @return string
     */
    public function getDetailsForNeuLog(Collection $arguments) : string {
        $strArr = [
            'message' => sprintf('%s', $arguments->get('message')),
            'fields' => [],
            'responsible_table' => $this->getTable(),
            'record_id' => (string) $this->id,
            // this needs to be the id of the logged in user, not the user being updated
            // UPDATE for NSN-1294, now it's the logged in user who did the action, or the user being updated
            // depending on the action
            // the company id that the user is now switching to is not added to the log as it is not needed at
            // this point
            'company_id' => (string) $arguments->get('company_id')
        ];
        if ($arguments->get('name') !== null) {
            $strArr['fields']['name'] = sprintf('Name : %s', $arguments->get('name'));
        }
        if ($arguments->get('email') !== null) {
            $strArr['fields']['email'] = sprintf('Email : %s', $arguments->get('email'));
        }
        if ($arguments->get('company_name') !== null) {
            $strArr['fields']['company_name'] = sprintf('Company : %s', $arguments->get('company_name'));
        }
        if ($arguments->get('role') !== null) {
            $strArr['fields']['role'] = sprintf('Role : %s', $arguments->get('role'));
        }

        return json_encode($strArr);
    }

}
