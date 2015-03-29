<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace PipingBag\Test\Di;

use Cake\TestSuite\TestCase;
use PipingBag\Di\PipingBag;

/**
 * Tests the PipingBag utility
 *
 */
class PipingBagTest extends TestCase
{

    public function testCreate()
    {
        PipingBag::create();
        debug('a');
    }
}
