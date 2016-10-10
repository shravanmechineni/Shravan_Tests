<?php

use Utils\WebConnector;

class RND17NewPlatformLandingPageContext extends WebConnector
{


    /**
     * clicks link with link id, title, text or image alt
     * @Then I see teachers pdf :arg1 opened in a new tab
     */
    public function iSeeTeachersPdfOpenedInANewTab($page)
    {
        $windowNames = $this->getSession()->getWindowNames();
        if(count($windowNames) > 1) {
            $this->getSession()->switchToWindow($windowNames[2]);
            $this->assertSession()->addressEquals($this->locatePath($page));
        }
        $window = $this->getSession()->getWindowName();
        $this->getSession()->switchToWindow($windowNames[0]);
    }
}