<?php namespace DCarbone\PHPConsulAPI;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class HasSettableStringTags
 * @package DCarbone\PHPConsulAPI
 */
trait HasSettableStringTags {
    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags) {
        $this->Tags = $tags;
        return $this;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function addTag(string $tag) {
        $this->Tags[] = $tag;
        return $this;
    }
}