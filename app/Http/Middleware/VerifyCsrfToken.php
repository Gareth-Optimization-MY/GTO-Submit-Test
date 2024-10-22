<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'create_charge',
        'change_store_plan',
        'cancel_charge',
        'update_subscription_location',
        'get_current_charge_status',
        'check_billing',
        'save_setting',
        'gdpr_view_customer',
        'gdpr_delete_customer',
        'gdpr_delete_shop',
        'uninstall',
        'get_settings',
        'install_theme',
        'uninstall_theme',
        'install_theme_with_embedded',
        'get_malls',
        'generate-report-decide',
        'check-ftp-connection',
        'send_support_email',
    ];
}
