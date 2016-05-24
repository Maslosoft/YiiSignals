<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/signals
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 *
 */

namespace Maslosoft\Signals\Factories;

use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Signals\Interfaces\SlotAwareInterface;
use Maslosoft\Signals\Signal;

/**
 * SlotFactory
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SlotFactory
{

	public static function create(Signal $signals, $signal, $fqn, $injection)
	{
		// Clone signal, as it might be modified by slot
		$cloned = clone $signal;

		// Constructor injection
		if (true === $injection)
		{
			$slot = new $fqn($cloned);

			// Slot aware call
			if ($cloned instanceof SlotAwareInterface)
			{
				$cloned->setSlot($slot);
			}
			return $cloned;
		}

		// Check if class exists and log if doesn't
		if (!ClassChecker::exists($fqn))
		{
			$signals->getLogger()->debug(sprintf("Class `%s` not found while emiting signal `%s`", $fqn, get_class($signal)));
			return false;
		}

		// Other type injection
		$slot = new $fqn;

		// Slot aware call
		if ($cloned instanceof SlotAwareInterface)
		{
			$cloned->setSlot($slot);
		}

		if (strstr($injection, '()'))
		{
			// Method injection
			$methodName = str_replace('()', '', $injection);
			$slot->$methodName($cloned);
		}
		else
		{
			// field injection
			$slot->$injection = $cloned;
		}
		return $cloned;
	}

}