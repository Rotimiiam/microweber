<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Form\Models\Form;
use MicroweberPackages\Form\Models\FormData;
use MicroweberPackages\Form\Models\FormDataValue;
use Tests\DuskTestCase;

class ContactFormTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    public function testSubmit()
    {
        $siteUrl = $this->siteUrl;

        // Disable captcha
        save_option(array(
            'option_group' => 'contact_form_default',
            'module' => 'contact_form',
            'option_key' => 'disable_captcha',
            'option_value' => 'y'
        ));

        $this->browse(function (Browser $browser) use($siteUrl) {

            $uniqueId = time();

            $browser->visit($siteUrl . 'contact-us');
            $browser->pause('2000');
            $browser->scrollTo('#contactform');
            $browser->pause('3000');


            $browser->type('your-name', 'Bozhidar Slaveykov' . $uniqueId);
            $browser->type('e-mail-address', 'bobi'.$uniqueId.'@microweber.com');
            $browser->type('phone', $uniqueId);
            $browser->type('company', 'Microweber ORG'.$uniqueId);
            $browser->type('message', 'Hello, i\'m very happy to use this software.'.$uniqueId);

         //   $browser->attach('file-upload', userfiles_path() . '/templates/default/img/contact_icons.png');
          //  $browser->attach('file-upload2', userfiles_path() .  '/templates/default/img/contact_icons2.png');

            $browser->script('$("#contactform").submit()');

            $browser->waitForText('Your message successfully sent');
            $browser->assertSee('Your message successfully sent');

/*
            $findFormDataValues = FormDataValue::where('field_value', 'bobi'.$uniqueId.'@microweber.com')->get();
            foreach ($findFormDataValues as $formDataValue) {
                if ($formDataValue->form_data_id) {

                }
            }


            $this->assertEquals($findedData['Message'], 'Hello, i\'m very happy to use this software.'.$uniqueId);
            $this->assertEquals($findedData['Company'], 'Microweber ORG'.$uniqueId);
            $this->assertEquals($findedData['Phone'], $uniqueId);
            $this->assertEquals($findedData['Your Name'], 'Bozhidar Slaveykov' . $uniqueId);
            $this->assertEquals($findedData['E-mail Address'], 'bobi'.$uniqueId.'@microweber.com');*/

        });
    }
}
