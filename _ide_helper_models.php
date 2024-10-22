<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Location
 *
 * @property int $id
 * @property string $mall_id
 * @property string $location
 * @property string $shop
 * @property string|null $fields
 * @property string|null $cash
 * @property string|null $tng
 * @property string|null $visa
 * @property string|null $master_card
 * @property string|null $amex
 * @property string|null $vouchers
 * @property string|null $others
 * @property string|null $ftp_details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereAmex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereFtpDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereMallId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereMasterCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereTng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVisa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Location whereVouchers($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Mall
 *
 * @property int $id
 * @property string $title
 * @property string $country
 * @property int $template_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Mall newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mall newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Mall query()
 * @method static \Illuminate\Database\Eloquent\Builder|Mall whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mall whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mall whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mall whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mall whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Mall whereUpdatedAt($value)
 */
	class Mall extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Reports
 *
 * @property int $id
 * @property int|null $template_use
 * @property string|null $location
 * @property string|null $input_fields
 * @property string|null $ftp_details
 * @property string|null $report_type
 * @property string|null $report_date
 * @property int $is_queued
 * @property string|null $report_to_date
 * @property string|null $schedule_cron
 * @property string|null $report_id
 * @property string|null $last_run
 * @property string|null $shop
 * @property string|null $filename
 * @property int|null $mall_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Reports newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reports newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Reports query()
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereFtpDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereInputFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereIsQueued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereLastRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereMallId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereReportDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereReportToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereScheduleCron($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereTemplateUse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Reports whereUpdatedAt($value)
 */
	class Reports extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Store
 *
 * @property int $id
 * @property string $shop_url
 * @property string $shopify_token
 * @property int|null $current_charge_id
 * @property string|null $trial_expiration_date
 * @property string|null $settings
 * @property string $plan_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCurrentChargeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereShopUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereShopifyToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTrialExpirationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 */
	class Store extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Template
 *
 * @property int $id
 * @property string $name
 * @property string $seprator
 * @property string $fields
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Template newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Template query()
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereSeprator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Template whereUpdatedAt($value)
 */
	class Template extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Variable
 *
 * @property int $id
 * @property string $mall_id
 * @property string|null $cash
 * @property string|null $tng
 * @property string|null $visa
 * @property string|null $master_card
 * @property string|null $amex
 * @property string|null $vouchers
 * @property string|null $others
 * @property string $shop
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Variable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereAmex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereMallId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereMasterCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereOthers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereShop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereTng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereVisa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variable whereVouchers($value)
 */
	class Variable extends \Eloquent {}
}

