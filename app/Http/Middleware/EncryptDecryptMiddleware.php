<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Crypt;

class EncryptDecryptMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Decrypt the request data
        if ($request->isMethod('post') || $request->isMethod('put')) {
            $decryptedData = [];
            foreach ($request->all() as $key => $value) {
                try {
                    $decryptedData[$key] = Crypt::decryptString($value);
                } catch (\Exception $e) {
                    // If decryption fails, use the original value
                    $decryptedData[$key] = $value;
                }
            }
            $request->merge($decryptedData);
        }

        $response = $next($request);

        // Encrypt the response data
        if ($response->isSuccessful()) {
            $originalContent = $response->getOriginalContent();
            if (is_array($originalContent)) {
                $encryptedContent = [];
                foreach ($originalContent as $key => $value) {
                    $encryptedContent[$key] = Crypt::encryptString((string) $value);
                }
                $response->setContent(json_encode($encryptedContent));
            }
        }

        return $response;
    }
}
