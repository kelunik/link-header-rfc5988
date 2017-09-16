<?php

namespace Kelunik\LinkHeaderRfc5988;

final class Link {
    private $uri;
    private $params;

    public function __construct(string $uri, array $params) {
        $this->uri = $uri;
        $this->params = $params;
    }

    public function getUri(): string {
        return $this->uri;
    }

    public function getParams(): array {
        return $this->params;
    }

    public function getParam(string $name): string {
        return $this->params[$name] ?? "";
    }
}
