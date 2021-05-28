<?php
/**
 * Created By PhpStorm.
 * User : Latent
 * Date : 2021/5/27
 * Time : 3:52 下午
 **/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mews\Captcha\Captcha;

class CaptchaController extends Controller
{

    public function captcha()
    {
        return $this->success(['captcha'=>app('captcha')->create('default', true)]);
    }

}
