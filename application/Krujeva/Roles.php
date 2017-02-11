<?php

namespace Krujeva {

	class Roles extends \Enum {
		const Root = 1; // root
		const HairUser = 2; // пользователь парикмахер
		const DealerUser = 3; // пользователь дилер
		const BonusUser = 4; // пользователь бонусы принимает

		public static function access($roleid) {
			$access = [];

			switch((int)$roleid) {
				case static::HairUser:
					static::hairAccess($access);
					break;
				case static::DealerUser:
					static::dealerAccess($access);
					break;
				case static::BonusUser:
					static::bonusAccess($access);
					break;
			}

			return $access;
		}

		private static function hairAccess(&$access) {

			$crud = [
				\Security\AccessMode::Insert,
				\Security\AccessMode::Update,
				\Security\AccessMode::Read,
				\Security\AccessMode::Remove
			];

			//dictionary
			$access['Data.Krujeva.Orders'] = [\Security\AccessMode::Insert];
		}

		private static function dealerAccess(&$access) {

			$crud = [\Security\AccessMode::Insert, \Security\AccessMode::Update, \Security\AccessMode::Read, \Security\AccessMode::Remove];

			//dictionary
			$access['Data.Krujeva.Dealers'] = [\Security\AccessMode::Update, \Security\AccessMode::Read];
			$access['Data.Krujeva.Orders'] = [\Security\AccessMode::Update, \Security\AccessMode::Read];
		}

		private static function bonusAccess(&$access) {

			$crud = [\Security\AccessMode::Insert, \Security\AccessMode::Update, \Security\AccessMode::Read, \Security\AccessMode::Remove];

			//dictionary
			$access['Data.Krujeva.BonusOrders'] = [\Security\AccessMode::Update, \Security\AccessMode::Read];
		}

		public static function mobileUserData() {

			global $application;

			$mobile = [];

			if (isset($application->configuration['mobile'])) {
				$mobile = $application->configuration['mobile'];
			}

			$data = [
				'regionid' => context('userProfile.regionid'),
				'userid' => context('user.id'),
				'status' => context('userProfile.status'),
				'phone' => context('userProfile.phone'),
				'organizationname' => context('userProfile.organizationname'),
				'inn' => context('userProfile.inn'),
				'barbershops' => context('barbershops'),
				'name' => context('userProfile.name'),
				'surname' => context('userProfile.surname'),
				'bonus' => \Krujeva\Context::getBonus(context('user.id')),
				'mobile' => $mobile
			];

			return $data;
		}

	}
}
