<?php

/**
 * This software package is licensed under `AGPL, Commercial` license[s].
 *
 * @package maslosoft/signals
 * @license AGPL, Commercial
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 * @link https://maslosoft.com/signals/
 */

namespace Maslosoft\Signals\Application\Commands;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Signals\Signal;
use Maslosoft\Signals\Utility;
use Maslosoft\Sitcom\Command;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * SignalsCommand
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 * @codeCoverageIgnore
 */
class BuildCommand extends ConsoleCommand implements AnnotatedInterface
{

	protected function configure()
	{
		$this->setName("build");
		$this->setDescription("Build signals list");
		$this->setDefinition([
		]);

		$help = <<<EOT
The <info>build</info> command will scan files for signals and save them to file.
EOT;
		$this->setHelp($help);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$signal = new Signal();
		(new Utility($signal))->generate();
	}

	/**
	 * @SlotFor(Maslosoft\Sitcom\Command)
	 * @param Command $signal
	 */
	public function reactOn(Command $signal)
	{
		$signal->add($this, 'signals');
	}

}
