<?php

namespace Security\Auth {
	use Security\Data\LDAPSettings;
	use Security\Data\LDAPUsers;
	use Security\Data\Roles;
	use Security\Data\Users;
	use System\Exception;

	class LDAP implements IAuth {
		static $configuration;
		static $connect;

		/**
		 * @param Context $context
		 * @param array $options
		 * @return bool
		 */
		public static function auth($context, $options = []) {
			if (isset($options['login']) && isset($options['password'])) {
				static::$configuration = LDAPSettings::getConfiguration();

				$accountName = $options['login'];
				$password = $options['password'];

				try {
					if (static::connect(static::$configuration['adminDN'], static::$configuration['adminPassword'])) {
						$userEntry = static::getUserEntry($accountName);

						if ($userEntry) {
							$userDn = ldap_get_dn(static::$connect, $userEntry);

							if (static::connect($userDn, $password)) {
								$userAttributes = ldap_get_attributes(static::$connect, $userEntry);

								if ($userAttributes) {
									$accountName = $userAttributes[static::$configuration['userAccountNameAttribute']][0];

									$ldapUser = LDAPUsers::firstBy(['accountname' => $accountName]);

									if (!$ldapUser) {

										$displayName = isset($userAttributes['displayName'][0]) ? $userAttributes['displayName'][0] : $accountName;

										$ldapUser = LDAPUsers::insertUser($accountName, $displayName);
									}

									$user = Users::get($ldapUser['userid'], ['as' => 'users', 'fields' => 'users.*, null as password']);

									$context->setUser($user);

									if (isset($userAttributes['memberOf']) && $userAttributes['memberOf']['count'] > 0) {
										$memberOf = $userAttributes['memberOf'];

										$groupNames = [];
										for ($i = 0; $i < $memberOf['count']; $i++) {
											$groupNames[] = "'" . $memberOf[$i] . "'";
										}

										if ($groupNames) {
											$roles = Roles::select([
												'fields' => 'roles.*',
												'join' => [
													[
														'table' => 'security.ldaproles',
														'on' => "ldaproles.roleid = roles.id",
														'type' => 'inner'
													]
												],
												'where' => 'ldaproles.dn in (' . implode(',', $groupNames) . ')'
											]);

											$access = [];
											foreach ($roles as $role) {
												Roles::readAccess($role);

												foreach ($role['access'] as $item) {
													$key = $item['name'];

													if (!isset($access[$key])) {
														$access[$key] = [];
													}

													$access[$key] = array_merge($access[$key], pgIntArrayDecode($item['mode']));
												}
											}

											$context->data['role'] = [
												'name' => 'LDAP',
												'access' => $access
											];
										}
									}

									return true;
								}
							}
						}
					}
				} catch (Exception $e) {
					//echo $e->getMessage();
				}

				return false;
			}
		}

		private static function connect($dn, $password) {
			static::$connect = ldap_connect(static::$configuration['host'], static::$configuration['port']);

			ldap_set_option(static::$connect, LDAP_OPT_PROTOCOL_VERSION, static::$configuration['protocolVersion']);

			if (!static::$connect) {
				throw new \System\Exception(sprintf('Cannot connect to LDAP server %s', static::$configuration['host']), \System\Code::LDAPError);
			}

			if (!@ldap_bind(static::$connect, $dn, $password)) {
				throw new \System\Exception('Wrong login or password', \System\Code::LDAPError);
			}

			return true;
		}

		private static function getUserEntry($login) {
			$loginChunk = sprintf(static::$configuration['userAccountNameAttribute'] . '=%s', $login);

			// возращает результат только для тех пользователей которы принадлежат группе usersGroupDN
			$condition = '(&' . static::$configuration['usersClassCondition'] . '(memberOf=' . static::$configuration['usersGroupDN'] . ')(' . $loginChunk . '))';

			$query = ldap_search(
				static::$connect,
				static::$configuration['usersBaseDN'],
				$condition
			);

			return ldap_first_entry(static::$connect, $query);
		}
	}
}
