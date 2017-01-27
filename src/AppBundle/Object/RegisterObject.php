<?php

namespace AppBundle\Object;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterObject {
	/**
	* @Assert\NotBlank()
	* @Assert\Type("string")
	*/
	private $fullName;
	/**
	* @Assert\NotBlank()
	* @Assert\Type("string")
	* @Assert\Regex("/^\w+\@\w+$/") //@TODO VALIDATION FOR EMAIL
	*/
	private $email;

	/**
	* @Assert\NotBlank()
	* @Assert\Type("string")
	* @Assert\GreaterThan(5) //@TODO VALIDATION FOR CONTAINING DIFFERENT TYPE OF CHARACTERS
	*/
	private $password;

	/**
	* @Assert\NotBlank()
	* @Assert\Type("string")
	* @Assert\GreaterThan(5) //@TODO VALIDATION FOR CONTAINING DIFFERENT TYPE OF CHARACTERS
	* @Assert\Expression(
		"this.getPassword() == this.getPassword2()", 
		message = "Password does not match"
	)
	*/
	private $password2;

	/**
	* @Assert\Type("bool")
	* @Assert\IsTrue()
	*/
	private $agree;

	/**
     * Get fullName
     *
     * @return string
     */
	public function getFullName() {
		return $this->fullName;
	}

	/**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return RegisterObject
     */
	public function setFullName($fullName) {
		$this->fullName = $fullName;

		return $this;
	}

	/**
     * Get email
     *
     * @return string
     */
	public function getEmail() {
		return $this->email;
	}

	/**
     * Set email
     *
     * @param string $email
     *
     * @return RegisterObject
     */
	public function setEmail($email) {
		$this->email = $email;

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
     * @return RegisterObject
     */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
     * Get password2
     *
     * @return string
     */
	public function getPassword2() {
		return $this->password2;
	}

	/**
     * Set password2
     *
     * @param string $password2
     *
     * @return RegisterObject
     */
	public function setPassword2($password2) {
		$this->password2 = $password2;

		return $this;
	}

	/**
     * Get agree
     *
     * @return bool
     */
	public function getAgree() {
		return $this->agree;
	}

	/**
     * Set agree
     *
     * @param bool $agree
     *
     * @return RegisterObject
     */
	public function setAgree($agree) {
		$this->agree = $agree;

		return $this;
	}
}