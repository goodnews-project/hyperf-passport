<?php

namespace Richard\HyperfPassport\Controller;

use Hyperf\Collection\Collection;
use Richard\HyperfPassport\Passport;

class ScopeController {

    /**
     * Get all of the available scopes for the application.
     *
     * @return Collection
     */
    public function all() {
        $passport = make(\Richard\HyperfPassport\Passport::class);
        return $passport->scopes();
    }

}
