<?php

namespace App\Services\Admin;

use App\Models\Award;
use App\Models\AwardCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AwardCodeService
{
    private const CHUNK_SIZE = 500;
    private const CODE_LENGTH = 16;

    public function generate(
        Award $award,
        int $quantity,
        ?string $product = null,
        ?string $validity = null
    ): int {
        return DB::transaction(function () use (
            $award,
            $quantity,
            $product,
            $validity
        ) {
            $rows = [];
            $generatedCodes = [];
            $now = now();

            while (count($rows) < $quantity) {

                $remaining = $quantity - count($rows);

                /*
                 * Generamos candidatos únicos
                 * dentro de este mismo proceso.
                 */
                $candidates = [];

                while (count($candidates) < $remaining) {

                    $code = Str::upper(
                        Str::random(self::CODE_LENGTH)
                    );

                    if (
                        isset($generatedCodes[$code]) ||
                        isset($candidates[$code])
                    ) {
                        continue;
                    }

                    $candidates[$code] = true;
                }

                /*
                 * Comprobamos de una sola vez si
                 * alguno ya existe en la BD.
                 */
                $existingCodes = AwardCode::query()
                    ->whereIn(
                        'code',
                        array_keys($candidates)
                    )
                    ->pluck('code')
                    ->flip()
                    ->all();

                foreach (array_keys($candidates) as $code) {

                    if (isset($existingCodes[$code])) {
                        continue;
                    }

                    $generatedCodes[$code] = true;

                    $rows[] = [
                        /*
                         * IMPORTANTE:
                         * insert() no ejecuta el evento creating()
                         * del modelo, así que debemos crear el UUID.
                         */
                        'id' => (string) Str::uuid(),

                        'award_id' => $award->id,

                        'code' => $code,

                        'active' => false,

                        'user_id' => null,

                        'product' => $product,

                        'validity' => $validity,

                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    if (count($rows) >= $quantity) {
                        break;
                    }
                }
            }

            /*
             * 2000 códigos:
             *
             * antes:
             * 2000 INSERTS
             *
             * ahora:
             * 4 INSERTS de 500 registros.
             */
            foreach (
                array_chunk(
                    $rows,
                    self::CHUNK_SIZE
                ) as $chunk
            ) {
                AwardCode::insert($chunk);
            }

            return count($rows);
        });
    }
}