<?php

namespace Dennenboom\VerdantUI\Http\Controllers;

use Burtds\VatChecker\Exceptions\VatNumberNotFound;
use Burtds\VatChecker\Facades\VatChecker;
use Illuminate\Http\JsonResponse;

class VatNumberCheckController
{
    /**
     * Verify whether the provided VAT number exists.
     *
     * @param string $vatNumber
     * @return JsonResponse
     */
    public function verify(string $vatNumber): JsonResponse
    {
        try {
            $response = VatChecker::getRawVatInstance($vatNumber);

            if ($response === null) {
                throw new VatNumberNotFound();
            }

            return response()
                ->json(['data' => $response], 200);
        } catch (VatNumberNotFound) {
            return response()
                ->json(['message' => 'not found'], 404);
        }
    }
}