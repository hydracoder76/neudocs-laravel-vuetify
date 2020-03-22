<?php
/**
 * User: mlawson
 * Date: 11/12/18
 * Time: 2:15 PM
 */

namespace NeubusSrm\Repositories;

use NeubusSrm\Lib\Exceptions\NeuUserInvalidCredentialsException;
use NeubusSrm\Models\Auth\VerificationToken;

/**
 * Class VerificationTokenRepository
 * @package NeubusSrm\Repositories
 */
class VerificationTokenRepository implements NeuSrmRepository
{
	/**
	 * @return string
	 */
	public function getModelClass(): string {
		return VerificationToken::class;
	}

	/**
	 * @param int $tokenId
	 * @return VerificationToken
	 * @throws \Throwable
	 */
	public function getTokenById(int $tokenId) : VerificationToken {
		$token = VerificationToken::whereId($tokenId)->first();
		throw_if($token == null, NeuUserInvalidCredentialsException::class, 'Invalid attempt');

		return $token;
	}

	/**
	 * @param string $token
	 * @return VerificationToken
	 */
	public function saveNewToken(string $token) : VerificationToken {
		return VerificationToken::create([
			'token' => $token
		]);
	}


}