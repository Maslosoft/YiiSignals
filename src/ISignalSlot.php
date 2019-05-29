<?php /** @noinspection PhpDeprecationInspection */

/**
 * This software package is licensed under `AGPL-3.0-only, proprietary` license[s].
 *
 * @package maslosoft/signals
 * @license AGPL-3.0-only, proprietary
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/signals/
 */

namespace Maslosoft\Signals;

use Maslosoft\Signals\Interfaces\SlotInterface;

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
	 * Set signal coming from application
	 * @param ISignal $signal
	 */
	public function setSignal(ISignal $signal);

	/**
	 * Get result of signal
	 */
	public function result();
}
