<?php

namespace Kelunik\LinkHeaderRfc5988\Test;

use Kelunik\LinkHeaderRfc5988\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase {
    public function testConstructor() {
        $link = new Link("http://domain.name", ["param" => "value"]);
        $params = $link->getParams();

        $this->assertSame("http://domain.name", $link->getUri());
        $this->assertSame("value", $link->getParam("param"));
        $this->assertSame("value", $params["param"]);
    }
}
