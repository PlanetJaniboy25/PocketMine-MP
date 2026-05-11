<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\ThrowSound;
use function sqrt;

class FishingRod extends Durable{

	public function getMaxStackSize() : int{
		return 1;
	}

	public function getMaxDurability() : int{
		return 384;
	}

	public function onClickAir(Player $player, Vector3 $directionVector, array &$returnedItems) : ItemUseResult{
		$player->getWorld()->addSound($player->getLocation(), new ThrowSound());

		if($player->hasFiniteResources()){
			$this->applyDamage(1);
		}

		return ItemUseResult::SUCCESS;
	}

	public function onInteractEntity(Player $player, Entity $entity, Vector3 $clickVector) : bool{
		if($entity === $player){
			return false;
		}

		$delta = $player->getPosition()->subtractVector($entity->getPosition());
		$distanceSquared = $delta->lengthSquared();
		if($distanceSquared > 0.0001){
			$distance = sqrt($distanceSquared);
			$entity->setMotion($entity->getMotion()->add(
				$delta->x * 0.1,
				$delta->y * 0.1 + sqrt($distance) * 0.08,
				$delta->z * 0.1
			));
		}

		return !$player->hasFiniteResources() || $this->applyDamage(1);
	}
}
