<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Debug;

use Maslosoft\Modules\Ua\Validators\SsoCredentialsValidator;

/**
 * AbstractSettings
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class AbstractSettings
{

	public $label = '';

	/**
	 * @ApplicationValue('')
	 * @Label('{label}')
	 * @Renderer('Toggle')
	 * @var bool
	 */
	public $enabled = true;

	/**
	 * @ApplicationValue('')
	 * @Label('Use proxy')
	 * @Description('Use single sign-on proxy for easier configuration. Disable this to setup single sign-on with your own credentials.')
	 * @Renderer('Toggle')
	 * @var bool
	 */
	public $proxy = true;

	/**
	 * @ApplicationValue('')
	 * @var string
	 */
	public $registerUrl = '';

	/**
	 * @ApplicationValue('')
	 * @Validator(SsoCredentialsValidator)
	 * @Label('Client ID')
	 * @Hint('For credentials visit: {registerUrl}')
	 * @see SsoCredentialsValidator
	 * @var string
	 */
	public $clientId = '';

	/**
	 * @ApplicationValue('')
	 * @Label('Client Secret')
	 * @Validator(SsoCredentialsValidator)
	 * @--Hint("Fill this value only if you want to change it")
	 * @Renderer('Password', autocomplete = false)
	 * @Secret()
	 * @ToJson(true)
	 * @see SsoCredentialsValidator
	 * @var string
	 */
	public $clientSecret = '';

}
