<?php
/**
 * Created by PhpStorm.
 * User: mlawson
 * Date: 2019-03-07
 * Time: 13:46
 */

namespace Tests\Browser\Login;

use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
use Tests\DuskTestCase;

/**
 * Class MfaSetupComponentQrTest
 * @package Tests\Browser\Login
 */
class MfaSetupComponentQrTest extends DuskTestCase
{

    /**
     * @throws \Throwable
     */
    public function testMfaSetupIt(): void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_qr_it_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_IT
        ]);

        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/admin/users')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu')
                ->assertPresent('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-login-btn')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testMfaSetupClient(): void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_qr_client_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_CLIENT
        ]);
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/requests')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu')
                ->assertPresent('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-login-btn')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testMfaSetupClientAdmin(): void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_qr_client_admin_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_ADMIN
        ]);
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/requests')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu')
                ->assertPresent('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-login-btn')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testMfaSetupNotRequired(): void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_qr_neubus_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_NEUBUS
        ]);

        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertMissing('#neu-mfa-setup-cont')
                ->assertPresent('@vue-single-select')
                ->assertPresent('@neu-menu')
                ->assertMissing('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-mfa-verify-logout-btn')
                ->assertMissing('@neu-mfa-verify-btn')
                ->assertMissing('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testMfaSetupForNotRequired(): void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_no_qr_neubus_setup_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_NEUBUS
        ]);
        $srmUser->has_mfa = true;
        $srmUser->otp_secret = null;
        $srmUser->save();
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/requests')
                ->assertMissing('@vue-single-select')
                ->assertMissing('@neu-menu')
                ->assertPresent('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-login-btn')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr');
        });

    }

    /**
     * @throws \Throwable
     */
    public function testMfaAlreadySetupIt() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_no_qr_it_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_IT
        ]);
        $srmUser->has_mfa = true;
        $srmUser->otp_secret = str_random();
        $srmUser->save();
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPathIs('/login')
                ->assertMissing('#neu-mfa-setup-cont')
                ->assertMissing('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-mfa-verify-logout-btn')
                ->assertMissing('@neu-mfa-verify-btn')
                ->assertMissing('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testMfaAlreadySetupClient() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_no_qr_client_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_CLIENT
        ]);
        $srmUser->has_mfa = true;
        $srmUser->otp_secret = str_random();
        $srmUser->save();
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPathIs('/login')
                ->assertMissing('#neu-mfa-setup-cont')
                ->assertMissing('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-mfa-verify-logout-btn')
                ->assertMissing('@neu-mfa-verify-btn')
                ->assertMissing('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testMfaAlreadySetupClientAdmin() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_no_qr_client_admin_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_ADMIN
        ]);
        $srmUser->has_mfa = true;
        $srmUser->otp_secret = str_random();
        $srmUser->save();
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPathIs('/login')
                ->assertMissing('#neu-mfa-setup-cont')
                ->assertMissing('@neu-mfa-setup-form-qr')
                ->assertMissing('@neu-mfa-verify-logout-btn')
                ->assertMissing('@neu-mfa-verify-btn')
                ->assertMissing('#neu-mfa-qr');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testLogoutButtonIt() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_logout_it_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_IT
        ]);
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/requests')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr')
                ->click('@neu-mfa-verify-logout-btn')
                ->pause(2000)
                ->assertPathIs('/login');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testLogoutButtonClient() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_logout_client_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_CLIENT
        ]);
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/requests')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr')
                ->click('@neu-mfa-verify-logout-btn')
                ->pause(2000)
                ->assertPathIs('/login');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testLogoutButtonClientAdmin() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'set_logout_client_admin_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_ADMIN
        ]);
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->assertSeeIn('#neu-mfa-setup-cont','Set up Multi-Factor Authentication')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Please set up and confirm multi-factor authentication with a separate device. Steps are as follows:')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you don\'t already, you will need to have the Google Authenticator app installed on a mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Android device, search for Google Authenticator in Google Play.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If you elect to use an Apple device, search for Google Authenticator in the App Store.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'https://itunes.apple.com/us/app/google-authenticator/id388497605')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Launch the Google Authenticator app on your mobile device.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'If this is your first time using the Google Authenticator app, you may already be on the "Add an account" screen. If so, move to step 4. If not, tap the "Add Account" button.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'When on the "Add an account" screen, tap "Scan a barcode".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Use your mobile device\'s camera, and the interface inside the Google Authenticator app, to scan the on-screen 2-D barcode below.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'The app should display a screen saying "Account added", with a large six-digit number, and the words "Neubus SRM (Neubus SRM)".')
                ->assertSeeIn('#neu-mfa-setup-cont', 'You\'ll need to return to this Google Authenticator')
                ->assertSeeIn('#neu-mfa-setup-cont', 'to get a code to authenticate your valid access of the SRM system.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'In order to ensure that Google Authenticator is configured correctly,')
                ->assertSeeIn('#neu-mfa-setup-cont', 'now we\'ll test the authentication to ensure SRM is linked up correctly to your Google Authenticator account.')
                ->assertSeeIn('#neu-mfa-setup-cont', 'Click "Continue" below.')
                ->assertPathIsNot('/requests')
                ->assertPresent('@neu-mfa-verify-logout-btn')
                ->assertPresent('@neu-mfa-verify-btn')
                ->assertPresent('#neu-mfa-qr')
                ->click('@neu-mfa-verify-logout-btn')
                ->pause(2000)
                ->assertPathIs('/login');
        });
    }

    public function testAltModalText() : void {
        $srmUser = factory(User::class)->create([
            'email' => 'alt_admin_' . DuskTestCase::USER_EMAIL,
            'role' => User::ROLE_ADMIN,
            'password' => bcrypt('secret')
        ]);
        $this->browse(function (Browser $browser) use ($srmUser) {
            $browser->visitRoute('login.form.view')
                ->type('@email', $srmUser->email)
                ->type('@password', 'secret')
                ->click('@neu-login-btn')
                ->pause(2000)
                ->assertPresent('#neu-mfa-setup-cont')
                ->click('@neu-mfa-verify-secret-btn')
                ->waitForText('2fA Alternative Setup')
                ->assertSee('2fA Alternative Setup')
                ->assertSee('The code has been refreshed and you may now see the secret key which you can manually enter into the Google Authenticator app.')
                ->assertSee('Tap the + button in the mobile app to start adding a new entry, then tap \'Enter a provided key\'.')
                ->assertSee('Then, in the respective two fields on that resulting screen, enter your email address and enter the Secret Key we\'re about to generate.')
                ->assertSee('For the dropdown in the app, select "Time based".');
        });
        $srmUser->delete();
    }

}
