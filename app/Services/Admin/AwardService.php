<?php

namespace App\Services\Admin;

use App\Models\Award;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class AwardService
{
    /**
     * Crea o actualiza el premio asociado a un modelo.
     *
     * Ejemplos:
     * FlappyGame
     * SmashGame
     * TriviaGame
     * MemoryGame
     */
    public function saveFor(
        Model $awardable,
        array $data
    ): Award {
        if (!method_exists($awardable, 'award')) {
            throw new InvalidArgumentException(
                sprintf(
                    'El modelo %s no tiene una relación award().',
                    get_class($awardable)
                )
            );
        }

        return DB::transaction(function () use (
            $awardable,
            $data
        ) {
            return $awardable
                ->award()
                ->updateOrCreate(
                    [],
                    [
                        'title' => $data['title'],
                        'content' => $data['content'],
                    ]
                );
        });
    }

    /**
     * Actualiza un premio existente.
     */
    public function update(
        Award $award,
        array $data
    ): Award {
        $award->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        return $award->refresh();
    }

    /**
     * Elimina un premio.
     *
     * No lo utilizaremos todavía.
     */
    public function delete(
        Award $award
    ): void {
        $award->delete();
    }
}