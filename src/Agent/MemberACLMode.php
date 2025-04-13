<?php

declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Agent;

/*
   Copyright 2016-2025 Daniel Carbone (daniel.p.carbone@gmail.com)

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

enum MemberACLMode: string
{
    // Disabled indicates that ACLs are disabled for this agent
    case Disabled = "0";

    // Enabled indicates that ACLs are enabled and operating in new ACL
    // mode (v1.4.0+ ACLs)
    case Enabled = "1";

    // Legacy has been deprecated, and will be treated as ACLModeUnknown.
    case Legacy = "2";

    // Unknown is used to indicate that the AgentMember.Tags didn't advertise
    // an ACL mode at all. This is the case for Consul versions before v1.4.0 and
    // should be treated the same as ACLModeLegacy.
    case Unknown = "3";
}