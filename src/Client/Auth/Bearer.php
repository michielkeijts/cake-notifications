<?php
declare(strict_types=1);

namespace CakeNotifications\Client\Auth;

use Cake\Http\Client\Request;

/**
 * Bearer authentication adapter for Cake\Http\Client
 *
 * Generally not directly constructed, but instead used by Cake\Http\Client
 * when $options['auth']['type'] is 'bearer'
 */
class Bearer
{
    /**
     * Add Authorization header to the request.
     *
     * @param \Cake\Http\Client\Request $request Request instance.
     * @param array $credentials Credentials.
     * @return \Cake\Http\Client\Request The updated request.
     * @see https://www.ietf.org/rfc/rfc2617.txt
     */
    public function authentication(Request $request, array $credentials): Request
    {
        if (isset($credentials['token'])) {
            $value = $this->_generateHeader($credentials['token']);
            /** @var \Cake\Http\Client\Request $request */
            $request = $request->withHeader('Authorization', $value);
        }

        return $request;
    }

    /**
     * Proxy Authentication
     *
     * @param \Cake\Http\Client\Request $request Request instance.
     * @param array $credentials Credentials.
     * @return \Cake\Http\Client\Request The updated request.
     * @see https://www.ietf.org/rfc/rfc2617.txt
     */
    public function proxyAuthentication(Request $request, array $credentials): Request
    {
        if (isset($credentials['token'])) {
            $value = $this->_generateHeader($credentials['token']);
            /** @var \Cake\Http\Client\Request $request */
            $request = $request->withHeader('Proxy-Authorization', $value);
        }

        return $request;
    }

    /**
     * Generate bearer [proxy] authentication header
     *
     * If $token contains `Bearer`, it is not added again
     * 
     * @param string $token.
     * @return string
     */
    protected function _generateHeader(string $token): string
    {
        if (strpos(strtolower($token), 'bearer' ) === FALSE) {
            return 'Bearer ' . $token;
        }
        return $token;
    }
}
