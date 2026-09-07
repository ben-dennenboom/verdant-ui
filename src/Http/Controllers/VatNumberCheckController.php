<?php

namespace Dennenboom\VerdantUI\Http\Controllers;

use Burtds\VatChecker\Exceptions\CountryCodeNotSupported;
use Burtds\VatChecker\Exceptions\VatNumberNotFound;
use Burtds\VatChecker\Facades\VatChecker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class VatNumberCheckController
{
    protected const NOT_DISCLOSED = '---';

    public function verify(Request $request): JsonResponse
    {
        $vatNumber = $this->normalise($request->query('vatNumber'));

        if ($vatNumber === null) {
            return response()
                ->json(['message' => 'invalid'], 422);
        }

        try {
            $vatObject = VatChecker::getRawVatInstance($vatNumber);

            $properties = json_decode($vatObject, true, 512, JSON_THROW_ON_ERROR);
        } catch (VatNumberNotFound|CountryCodeNotSupported) {
            return response()
                ->json([
                    'message' => 'not found',
                ], 404);
        } catch (Throwable $exception) {
            report($exception);

            return response()
                ->json([
                    'message' => 'verification unavailable',
                ], 502);
        }

        return response()
            ->json([
                'data' => [
                    'countryCode' => $properties['countryCode'] ?? null,
                    'vatNumber'   => $properties['vatNumber'] ?? null,
                    'name'        => $this->disclosed($properties['name'] ?? null),
                    'address'     => $this->disclosed($properties['address'] ?? null),
                ],
            ], 200);
    }

    protected function normalise(mixed $vatNumber): ?string
    {
        if (! is_string($vatNumber)) {
            return null;
        }

        $vatNumber = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $vatNumber));
        $length = strlen($vatNumber);

        return ($length >= 3 && $length <= 20)
            ? $vatNumber
            : null;
    }

    protected function disclosed(?string $value): ?string
    {
        $value = trim((string) $value);

        return ($value === '' || $value === self::NOT_DISCLOSED)
            ? null
            : $value;
    }
}
