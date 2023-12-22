<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2023 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use DCarbone\PHPConsulAPI\AbstractModel;

class TransparentProxyConfig extends AbstractModel
{
    /** @var int  */
    public int $OutboundListenerPort = 0;
    /** @var bool  */
    public bool $DialedDirectly = false;

    /**
     * @return int
     */
    public function getOutboundListenerPort(): int
    {
        return $this->OutboundListenerPort;
    }

    /**
     * @param int $OutboundListenerPort
     * @return TransparentProxyConfig
     */
    public function setOutboundListenerPort(int $OutboundListenerPort): TransparentProxyConfig
    {
        $this->OutboundListenerPort = $OutboundListenerPort;
        return $this;
    }
}
