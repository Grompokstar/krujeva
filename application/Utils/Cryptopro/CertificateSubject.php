<?php
namespace Utils\Cryptopro;

/**
 * Class CertificateSubject
 * @package Utils\Cryptopro
 *
 * @property string $C
 * @property string $O
 * @property string $CN
 * @property string $T
 * @property string $G
 * @property string $SN
 */
class CertificateSubject extends \ArrayObject
{
	public function getPost()
	{
		return $this->T;
	}

	public function getOrganization()
	{
		return $this->O;
	}

	public function getFio()
	{
		return !empty($this->SN) && !empty($this->G) ? "$this->SN $this->G" : $this->CN;
	}
}