<?php

namespace Richard\HyperfPassport\Controller;

use Hyperf\HttpServer\Request;
use Richard\HyperfPassport\Bridge\User;
use function Hyperf\Tappable\tap;

trait RetrievesAuthRequestFromSession {

    /**
     * Make sure the auth token matches the one in the session.
     *
     * @param  \Hyperf\HttpServer\Request  $request
     * @return void
     *
     * @throws \Richard\HyperfPassport\Exception\PassportException
     */
    protected function assertValidAuthToken(Request $request) {
        if ($request->has('auth_token') && $this->session->get('authToken') !== $request->input('auth_token')) {
            $exception = new \Richard\HyperfPassport\Exception\PassportException('The provided auth token['.$request->input('auth_token').'] for the request is different from the session auth token['.$this->session->get('authToken').'].');
            $exception->setStatusCode(400);
            $this->session->forget(['authToken', 'authRequest']);
            throw $exception;
        }
    }

    /**
     * Get the authorization request from the session.
     *
     * @param  \Hyperf\HttpServer\Request  $request
     * @return \League\OAuth2\Server\RequestTypes\AuthorizationRequest
     *
     * @throws \Exception
     */
    protected function getAuthRequestFromSession(Request $request) {
        return tap($this->session->get('authRequest'), function ($authRequest) use ($request) {
            if (!$authRequest) {
                $exception = new \Richard\HyperfPassport\Exception\PassportException('Authorization request was not present in the session.');
                $exception->setStatusCode(400);
                throw $exception;
            }
            $user = $this->auth->guard()->user();
            $authRequest->setUser(new User($user->getKey()));

            $authRequest->setAuthorizationApproved(true);
        });
    }

}
