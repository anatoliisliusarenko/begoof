<?php

namespace AppBundle\Object;

use Symfony\Component\Validator\Constraints as Assert;

class LoginObject {
	/**
	* @Assert\NotBlank()
	* @Assert\Type("string")
	*/
	private $username;

	/**
	* @Assert\NotBlank()
	* @Assert\Type("string")
	*/
	private $password;

	/**
	* @Assert\Type("bool")
	*/
	private $rememberMe;

	/**
     * Get username
     *
     * @return string
     */
	public function getUsername() {
		return $this->username;
	}

	/**
     * Set username
     *
     * @param string $username
     *
     * @return LoginObject
     */
	public function setUsername($username) {
		$this->username = $username;

		return $this;
	}

	/**
     * Get password
     *
     * @return string
     */
	public function getPassword() {
		return $this->password;
	}

	/**
     * Set password
     *
     * @param string $password
     *
     * @return LoginObject
     */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
     * Get rememberMe
     *
     * @return bool
     */
	public function getRememberMe() {
		return $this->rememberMe;
	}

	/**
     * Set rememberMe
     *
     * @param bool $rememberMe
     *
     * @return LoginObject
     */
	public function setRememberMe($rememberMe) {
		$this->rememberMe = $rememberMe;

		return $this;
	}
}