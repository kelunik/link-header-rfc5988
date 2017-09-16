<?php

namespace Kelunik\LinkHeaderRfc5988;

final class Links {
    private $links;
    private $linksByRel;

    /** @internal */
    public function __construct(array $links) {
        $this->links = $links;

        foreach ($links as $link) {
            $rel = $link->getParam("rel");

            if ($rel !== "") {
                $rels = \explode(" ", $rel);

                foreach ($rels as $rel) {
                    $this->linksByRel[\strtolower($rel)][] = $link;
                }
            }
        }
    }

    /** @return Link[] */
    public function getAll(): array {
        return $this->links;
    }

    /** @return Link|null */
    public function getByRel(string $rel) {
        return $this->linksByRel[\strtolower($rel)][0] ?? null;
    }

    /** @return Link[] */
    public function getAllByRel(string $rel): array {
        return $this->linksByRel[\strtolower($rel)] ?? [];
    }
}