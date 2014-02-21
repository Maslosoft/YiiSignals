<?php

namespace Maslosoft\Signals;

use CComponent;
use EAnnotationUtility;

/**
 * Signals utility class
 *
 * @author Piotr
 */
class Utility extends CComponent
{

	const slotFor = 'SlotFor';
	const signalFor = 'SignalFor';

	private $_data = [];

	public function generate()
	{
		$annotations = [
			'SlotFor',
			'SignalFor'
		];
//		echo '<pre>';
		EAnnotationUtility::fileWalker($annotations, [$this, 'processFile']);

//		var_export($this->_data);
//		echo '</pre>';
		return $this->_data;
	}

	/**
	 * TODO Slot should be annotated at method level and store this method name in definiution file
	 * @param type $file
	 */
	public function processFile($file)
	{
		// Signals
		$class = EAnnotationUtility::rawAnnotate($file)['class'];
		$alias = EAnnotationUtility::getAliasOfPath($file);
		if (isset($class[self::signalFor]))
		{
			$val = $this->_getValuesFor($class[self::signalFor]);
			foreach ($val as $slot)
			{
				$this->_data[Signal::slots][$slot][$alias] = true;
			}
		}

		// Slots
		// For constructor injection
		if (isset($class[self::slotFor]))
		{
			$val = $this->_getValuesFor($class[self::slotFor]);
			foreach ($val as $slot)
			{
				$this->_data[Signal::signals][$slot][$alias] = true;
			}
		}

		// For method injection
		$methods = EAnnotationUtility::rawAnnotate($file)['methods'];
		foreach ($methods as $methodName => $method)
		{
			if (!isset($method[self::slotFor]))
			{
				continue;
			}
			$val = $this->_getValuesFor($method[self::slotFor]);
			foreach ($val as $slot)
			{
				$this->_data[Signal::signals][$slot][$alias] = sprintf('%s()', $methodName);
			}
		}

		// For property injection
		$fields = EAnnotationUtility::rawAnnotate($file)['fields'];
		foreach ($fields as $fieldName => $method)
		{
			if (!isset($method[self::slotFor]))
			{
				continue;
			}
			$val = $this->_getValuesFor($method[self::slotFor]);
			foreach ($val as $slot)
			{
				$this->_data[Signal::signals][$slot][$alias] = sprintf('%s', $fieldName);
			}
		}
	}

	private function _getValuesFor($src)
	{
		$value = [];
		foreach ($src as $val)
		{
			$val = $val['value'];
			if (is_array($val))
			{
				$value = array_merge($value, $val);
			}
			elseif (is_string($val))
			{
				$value[] = $val;
			}
		}
		return array_values(array_unique($value));
	}

}