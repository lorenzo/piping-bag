<?php
declare(strict_types=1);

namespace PipingBag\Console;

use Cake\Console\CommandFactory;
use Cake\Console\CommandInterface;
use Cake\Console\Shell;
use InvalidArgumentException;
use PipingBag\Di\PipingBag;

class DICommandFactory extends CommandFactory {

    /**
     * @return CommandInterface|Shell|mixed
     * @param string $className
     * @throws InvalidArgumentException
     */
    public function create(string $className) {
        // load the shell first to make sure it is a valid shell class
        parent::create($className);
        return PipingBag::get($className);
    }
}