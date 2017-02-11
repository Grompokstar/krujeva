<?php
namespace Utils\Cryptopro;

class VerifyResult
{
	const UNDEF = -1;
	const OK = 0;
	const WRONG_MODULE_VER = 1;
	const VERIFICATION_FAILURE = 2;
	const CERT_IS_NOT_TIME_VALID = 3;
	const CERT_IS_EXPIRED = 4;
	const CERT_IS_REVOKED = 5;

	public static $errors = [
		self::UNDEF => '-',
		self::OK => '',
		self::WRONG_MODULE_VER => '',
		self::VERIFICATION_FAILURE => 'подпись не верна',
		self::CERT_IS_NOT_TIME_VALID => 'срок действия сертификата не наступил',
		self::CERT_IS_EXPIRED => 'срок действия сертификата истек',
		self::CERT_IS_REVOKED => 'сертификат отозван',
	];

	private $code = self::UNDEF;
	private $message;
	private $serial;

	/**
	 * @var CertificateSubject
	 */
	private $subject;

	private $certificate_blob;

	/**
	 * @var \DateTime
	 */
	private $signing_time;

	/**
	 * @var \DateTime
	 */
	private $not_before;

	/**
	 * @var \DateTime
	 */
	private $not_after;

	/**
	 * @var \DateTime
	 */
	private $revocation_date;

	/**
	 * @return int
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param int $code
	 * @return $this
	 */
	public function setCode($code)
	{
		$this->code = $code;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param string $message
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSerial()
	{
		return $this->serial;
	}

	/**
	 * @param mixed $serial
	 * @return $this
	 */
	public function setSerial($serial)
	{
		$this->serial = $serial;
		return $this;
	}

	/**
	 * @return CertificateSubject
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param CertificateSubject $subject
	 * @return $this
	 */
	public function setSubject(CertificateSubject $subject)
	{
		$this->subject = $subject;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCertificateBlob()
	{
		return $this->certificate_blob;
	}

	/**
	 * @param mixed $certificate_blob
	 * @return $this
	 */
	public function setCertificateBlob($certificate_blob)
	{
		$this->certificate_blob = $certificate_blob;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getNotAfter()
	{
		return $this->not_after;
	}

	/**
	 * @param \DateTime $not_after
	 * @return $this
	 */
	public function setNotAfter($not_after)
	{
		$this->not_after = $not_after;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getNotBefore()
	{
		return $this->not_before;
	}

	/**
	 * @param \DateTime $not_before
	 * @return $this
	 */
	public function setNotBefore($not_before)
	{
		$this->not_before = $not_before;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getSigningTime()
	{
		return $this->signing_time;
	}

	/**
	 * @param \DateTime $signing_time
	 * @return $this
	 */
	public function setSigningTime($signing_time)
	{
		$this->signing_time = $signing_time;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getRevocationDate()
	{
		return $this->revocation_date;
	}

	/**
	 * @param \DateTime $revocation_date
	 * @return $this
	 */
	public function setRevocationDate($revocation_date)
	{
		$this->revocation_date = $revocation_date;
		return $this;
	}

	public function getErrorAsString()
	{
		if (isset(self::$errors[$this->getCode()])) {
			return self::$errors[$this->getCode()];
		}

		return '';
	}
}