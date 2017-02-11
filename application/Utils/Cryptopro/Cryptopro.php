<?php
namespace Utils\Cryptopro;

class Cryptopro
{
	public static $from_charset = 'CP1251';
	public static $to_charset = 'UTF8';

	public static function decodeSign($sign_blob)
	{
		if (false === ($sign_p7b = base64_decode(preg_replace('/\s/', '', $sign_blob), true)))
			$sign_p7b = $sign_blob;

		return $sign_p7b;
	}

	public static function recodeFields(array &$ret)
	{
		if (self::$from_charset != self::$to_charset) {
			foreach (['Issuer', 'Subject'] as $f)
				$ret[$f] = iconv(self::$from_charset, self::$to_charset, $ret[$f]);
		}
	}

	/**
	 * @param string $data
	 * @param string $sign_blob
	 * @return VerifyResult
	 */
	public static function verify($data, $sign_blob)
	{
		if (!version_compare(phpversion('cryptopro'), '1.56', '>=')) {
			return (new VerifyResult())->setCode(VerifyResult::WRONG_MODULE_VER);
		}

		try {
			$sign_p7b = self::decodeSign($sign_blob);
			if (empty($data) || empty($sign_p7b)) {
				return (new VerifyResult())->setCode(VerifyResult::VERIFICATION_FAILURE);
			}
			$verify_result = cryptopro_VerifyDetachedContent($data, $sign_p7b, [], false);
		} catch (\Exception $e) {
			return (new VerifyResult())
				->setCode(VerifyResult::VERIFICATION_FAILURE)
				->setMessage($e->getMessage());
		}

		if (!is_array($verify_result)) {
			return (new VerifyResult())->setCode(VerifyResult::VERIFICATION_FAILURE);
		}

		self::recodeFields($verify_result);

		$verify_result = array_merge([
			'Serial' => '',
			'CER' => '',
			'Subject' => '',
			'Signing time' => '',
			'Not Before' => '',
			'Not After' => '',
			'Revocation date' => '',
		], $verify_result);

		$result = (new VerifyResult())
			->setSerial($verify_result['Serial'])
			->setCertificateBlob($verify_result['CER'])
			->setSubject(new CertificateSubject(self::parseRDN($verify_result['Subject'])))
			->setSigningTime(self::parseDate($verify_result['Signing time']))
			->setNotBefore(self::parseDate($verify_result['Not Before']))
			->setNotAfter(self::parseDate($verify_result['Not After']))
			->setRevocationDate(self::parseDate($verify_result['Revocation date']))
			;

		if ($result->getSigningTime() < $result->getNotBefore()) {
			$result->setCode(VerifyResult::CERT_IS_NOT_TIME_VALID);
		} elseif ($result->getSigningTime() > $result->getNotAfter()) {
			$result->setCode(VerifyResult::CERT_IS_EXPIRED);
		} elseif ($result->getRevocationDate() && $result->getSigningTime() >= $result->getRevocationDate()) {
			$result->setCode(VerifyResult::CERT_IS_REVOKED);
		} else {
			$result->setCode(VerifyResult::OK);
		}

		return $result;
	}

	public static function parseRDN($rdn_string)
	{
		$ret = [];

		foreach (explode(', ', $rdn_string) as $part) {
			if (preg_match('/^(\w+)\s*=\s*(.*)$/', $part, $r)) {
				$ret[$r[1]] = $r[2];
			}
		}

		return new \ArrayObject($ret, \ArrayObject::ARRAY_AS_PROPS);
	}

	public static function parseDate($date)
	{
		$ret = (new \DateTime())->createFromFormat('Y-m-d H:i:s', $date);
		if ($ret === false) {
			$ret = null;
		}
		return $ret;
	}
}