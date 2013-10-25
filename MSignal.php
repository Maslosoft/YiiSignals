<?php
Yii::setPathOfAlias('yii-signals', __DIR__);
Yii::import('yii-signals.*');

/**
 * Description of MSignal
 *
 * @author Piotr
 */
class MSignal extends CApplicationComponent
{
	const slots = 'slots';
	const signals = 'signals';
	const ConfigFilename = 'signals-definition.php';

	/**
	 * Path alias of where to store signals definios
	 * @var string
	 */
	public $configAlias = 'autogen';

	/**
	 * 
	 */
	public $containerClass = 'ext.signals.MSignalContainer';

	/**
	 * Access control callback.
	 * Callback signature:
	 * <code><pre>public function(string $role, User $user)</pre></code>
	 * @var callback
	 */
	public $accessCallback = [];
	private static $_config = [];

	public function init()
	{
		if (!$this->isInitialized)
		{
			$configPath = Yii::getPathOfAlias('autogen');
			if (false === $configPath)
			{
				throw new RuntimeException('Alias "autogen" is not defined');
			}
			self::$_config = require $configPath . '/' . self::ConfigFilename;
		}
		parent::init();
	}

	/**
	 * Emit signal to inform slots
	 * @param object $signal
	 * @return object[]
	 */
	public function emit($signal)
	{
		$result = [];
		$name = get_class($signal);
		if(!isset(self::$_config[self::signals][$name]))
		{
			return $result;
		}
		foreach(self::$_config[self::signals][$name] as $alias => $injection)
		{
			// Skip
			if(false === $injection)
			{
				continue;
			}
			// Constructor injection
			if(true === $injection)
			{
				$result[] = Yii::createComponent($alias, $signal);
				continue;
			}
			
			$slot = Yii::createComponent($alias);
			if(strstr($injection, '()'))
			{
				// Method injection
				$methodName = str_replace('()', '', $injection);
				$slot->$methodName($signal);
			}
			else
			{
				// field injection
				$slot->$injection = $signal;
			}
			$result[] = $slot;
		}
		return $result;
	}
	
	/**
	 * Emit signal and get results from connected slots
	 * @param IMSignal $signal
	 * @return IMSignalSlot[] Slot container
	 */
	public function _old_emit(IMSignal $signal)
	{
		$class = get_class($signal);
		$result = [];
//		var_dump(self::$_config);
//		exit;
		foreach (self::$_config[$class] as $alias)
		{
			$slot = Yii::createComponent($alias);
			$slot->setSignal($signal);
			$container = new MSignalContainer();
			$container->result = $slot->result();
			$container->meta = EComponentMeta::create($slot);
			$result[] = $container;
		}
		return $result;
	}

	/**
	 * Call for signals from slot
	 * @param object $slot
	 */
	public function gather($slot)
	{
		$result = [];
		$name = get_class($slot);
		foreach(self::$_config[self::slots][$name] as $alias => $emit)
		{
			if(false === $emit)
			{
				continue;
			}
			$result[] = Yii::createComponent($alias);
		}
		return $result;
	}
}
