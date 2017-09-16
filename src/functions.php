<?php

namespace Kelunik\LinkHeaderRfc5988;

function parseLinks(string $input): Links {
    $links = [];
    $linkValues = \explode(",", $input);

    foreach ($linkValues as $linkValue) {
        $linkParams = \explode(";", $linkValue);

        if (\count($linkParams) < 2) {
            continue;
        }

        $uri = \trim(\array_shift($linkParams));

        if ($uri[0] !== "<" || $uri[\strlen($uri) - 1] !== ">") {
            continue;
        }

        $uri = \substr($uri, 1, -1);
        $params = [];

        foreach ($linkParams as $linkParam) {
            $paramSegments = \explode("=", \trim($linkParam));

            if (\count($paramSegments) !== 2) {
                continue;
            }

            if ($paramSegments[1][0] === "\"" && $paramSegments[1][\strlen($paramSegments[1]) - 1] === "\"") {
                $paramSegments[1] = \substr($paramSegments[1], 1, -1);
            }

            $params[$paramSegments[0]] = $paramSegments[1];
        }

        $links[] = new Link($uri, $params);
    }

    return new Links($links);
}
