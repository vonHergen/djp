<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2009-2010 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

class lw_view
{

    public function __construct($file = false)
    {
        $this->file = $file;
        $this->isFileExisting();
    }

    public function render()
    {
        $this->isFileExisting();
        ob_start();
        $this->includeFile($this->file);
        return ob_get_clean();
    }

    private function includeFile($file)
    {
        include $file;
    }
    
    private function isFileExisting()
    {
        if (!is_file($this->file)) {
            throw new Exception("Template-File is not existing !");
        }
    }

}
