<?php

/**
 * This software package is licensed under GNU LESSER GENERAL PUBLIC LICENSE license.
 *
 * @package maslosoft/signals
 * @licence GNU LESSER GENERAL PUBLIC LICENSE
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 *
 */

namespace Maslosoft\Signals;

use Maslosoft\Signals\Interfaces\SlotInterface;
use Maslosoft\Signals\ISignal;

/**
 * Signal slot interface
 * Use Interfaces\SlotInterface instead.
 * @see SlotInterface
 * @deprecated since version number
 * @author Piotr
 */
interface ISignalSlot
{

	/**
	 * Set signal comming from application
	 * @param ISignal $signal
	 */
	public function setSignal(ISignal $signal);

	/**
	 * Get result of signal
	 */
	public function result();
}
