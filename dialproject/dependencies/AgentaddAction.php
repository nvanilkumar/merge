<?php
/**
 * Originate action message.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Action
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @version    SVN: $Id$
 * @link       http://marcelog.github.com/PAMI/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
namespace PAMI\Message\Action;

/**
 * Originate action message.
 *
 * PHP Version 5
 *
 * @category   Pami
 * @package    Message
 * @subpackage Action
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/PAMI/ Apache License 2.0
 * @link       http://marcelog.github.com/PAMI/
 */
class AgentaddAction extends ActionMessage
{
    /**
     * Sets Exten key.
     *
     * @param string $extension Extension to use (requires Context and Priority).
     *
     * @return void
     */
    public function setAgentid($extension)
    {
        $this->setKey('Agentid', $extension);
    }

    /**
     * Sets Context key.
     *
     * @param string $context Context to use (requires Exten and Priority).
     *
     * @return void
     */
    public function setSecret($context)
    {
        $this->setKey('Secret', $context);
    }

    /**
     * Sets Priority key.
     *
     * @param string $priority Priority to use (requires Exten and Context).
     *
     * @return void
     */
    public function setName($priority)
    {
        $this->setKey('Name', $priority);
    }

    

    /**
     * Constructor.
     *
     * @param string $channel Channel to call to.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('agentadd');
       
    }
}
