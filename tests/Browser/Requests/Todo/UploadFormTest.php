<?php
/**
 * User: mlawson
 * Date: 2019-01-23
 * Time: 11:12
 */

namespace Tests\Browser\Requests\Todo;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use NeubusSrm\Models\Auth\User;
/**
 * Class UploadFormTest
 * @package Tests\Browser\Requests\Todo
 */
class UploadFormTest extends DuskTestCase
{


    /**
     * Pretty much a duplicate test, but I want to verify again
     * @throws \Throwable
     */
    public function testOpenForm() : void{

        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->click('@neu-btn')
                ->waitFor('@neu-upload-form')
                ->assertSee('Upload files')
                ->assertPresent('@neu-upload-form')
                ->assertPresent('footer .btn-primary')
                ->assertPresent('footer .btn-secondary')
                ->assertPresent('button.close');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testUploadFieldsPresent() : void {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->click('@neu-btn')
                ->waitFor('@neu-upload-form')
                ->assertSee('Upload files')
                ->assertPresent('@neu-upload-form')
                ->assertPresent('@neu-upload-file');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testFileFieldAccepts() : void {
        $this->markTestIncomplete('Needs further refactoring before viable');
        $user = factory(User::class)->create([
            'role' => User::ROLE_IT,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visitRoute('todo.upload')
                ->click('@neu-btn')
                ->waitFor('@neu-upload-form')
                ->assertPresent('@neu-upload-file')
            ;
        });
    }
}