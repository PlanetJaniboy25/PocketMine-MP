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

class FishingRod extends Durable{

	public function getMaxStackSize() : int{
		return 1;
	}

	public function getMaxDurability() : int{
		return 384;
	}

	public function onClickAir(Player $player, Vector3 $directionVector, array &$returnedItems) : ItemUseResult{
		if($player->hasFiniteResources()){
			$this->applyDamage(1);
		}

		return ItemUseResult::SUCCESS;
	}

	public function onInteractEntity(Player $player, Entity $entity, Vector3 $clickVector) : bool{
		if($entity === $player){
			return false;
		}

		$pullMotion = $player->getPosition()->subtractVector($entity->getPosition())->normalize()->multiply(0.4);
		$entity->setMotion($entity->getMotion()->add($pullMotion->x, $pullMotion->y + 0.2, $pullMotion->z));

		return !$player->hasFiniteResources() || $this->applyDamage(1);
	}
}
