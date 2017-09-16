<?php

namespace Kelunik\LinkHeaderRfc5988\Test;

use Kelunik\LinkHeaderRfc5988\Link;
use PHPUnit\Framework\TestCase;
use function Kelunik\LinkHeaderRfc5988\parseLinks;

class ParseTest extends TestCase {
    public function testRfc5988Example1() {
        $links = parseLinks("<http://example.com/TheBook/chapter2>; rel=\"previous\"; title=\"previous chapter\"");
        $link = $links->getAll()[0];

        $this->assertCount(1, $links->getAll());
        $this->assertSame("http://example.com/TheBook/chapter2", $link->getUri());
        $this->assertSame("previous", $link->getParam("rel"));
        $this->assertSame("previous chapter", $link->getParam("title"));
        $this->assertInstanceOf(Link::class, $links->getByRel("previous"));
        $this->assertNull($links->getByRel("next"));
    }

    public function testRfc5988Example2() {
        $links = parseLinks("</>; rel=\"http://example.net/foo\"");
        $link = $links->getAll()[0];

        $this->assertCount(1, $links->getAll());
        $this->assertSame("/", $link->getUri());
        $this->assertSame("http://example.net/foo", $link->getParam("rel"));
        $this->assertInstanceOf(Link::class, $links->getByRel("http://example.net/foo"));
        $this->assertNull($links->getByRel("foobar"));
    }

    public function testRfc5988Example3() {
        // Note: https://tools.ietf.org/html/rfc2231 is currently not decoded
        $links = parseLinks("</TheBook/chapter2>; rel=\"previous\"; title*=UTF-8'de'letztes%20Kapitel, </TheBook/chapter4>; rel=\"next\"; title*=UTF-8'de'n%c3%a4chstes%20Kapitel");
        list($link1, $link2) = $links->getAll();

        $this->assertCount(2, $links->getAll());

        $this->assertSame("/TheBook/chapter2", $link1->getUri());
        $this->assertSame("previous", $link1->getParam("rel"));
        $this->assertSame("UTF-8'de'letztes%20Kapitel", $link1->getParam("title*"));

        $this->assertSame("/TheBook/chapter4", $link2->getUri());
        $this->assertSame("next", $link2->getParam("rel"));
        $this->assertSame("UTF-8'de'n%c3%a4chstes%20Kapitel", $link2->getParam("title*"));

        $this->assertInstanceOf(Link::class, $links->getByRel("previous"));
        $this->assertInstanceOf(Link::class, $links->getByRel("next"));
        $this->assertNull($links->getByRel("foobar"));
    }

    public function testRfc5988Example4() {
        $links = parseLinks("<http://example.org/>; rel=\"start http://example.net/relation/other\"");
        $link = $links->getAll()[0];

        $this->assertCount(1, $links->getAll());
        $this->assertSame("http://example.org/", $link->getUri());
        $this->assertSame("start http://example.net/relation/other", $link->getParam("rel"));
        $this->assertInstanceOf(Link::class, $links->getByRel("http://example.net/relation/other"));
        $this->assertInstanceOf(Link::class, $links->getByRel("start"));
        $this->assertNull($links->getByRel("foobar"));
    }
}