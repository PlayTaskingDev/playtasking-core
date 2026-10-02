<?php

namespace App\Services\Admin;

use App\Models\MemoryCard;
use App\Models\MemoryQuiz;
use App\Traits\UploadImageTrait;
use Illuminate\Http\UploadedFile;

class MemoryCardService
{
    use UploadImageTrait;

    public function sync(
        MemoryQuiz $memoryQuiz,
        array $cards
    ): void {
        foreach ($cards as $cardData) {

            /*
             * Eliminar carta existente.
             */
            if (
                !empty($cardData['_delete'])
                && !empty($cardData['id'])
            ) {
                $memoryQuiz
                    ->memory_cards()
                    ->whereKey($cardData['id'])
                    ->delete();

                continue;
            }

            /*
             * Evitar crear filas completamente vacías.
             */
            if (
                blank($cardData['name'] ?? null)
                && empty($cardData['featured_image'])
            ) {
                continue;
            }

            $this->saveCard(
                $memoryQuiz,
                $cardData
            );
        }
    }

    private function saveCard(
        MemoryQuiz $memoryQuiz,
        array $data
    ): MemoryCard {
        if (!empty($data['id'])) {

            /*
             * Importantísimo:
             * solamente permitimos editar cartas
             * pertenecientes a este Memory.
             */
            $card = $memoryQuiz
                ->memory_cards()
                ->whereKey($data['id'])
                ->firstOrFail();

        } else {

            $card = new MemoryCard();
            $card->memory_quiz_id =
                $memoryQuiz->id;
        }

        $card->name =
            $data['name'] ?? null;

        if (
            isset($data['featured_image'])
            && $data['featured_image']
                instanceof UploadedFile
        ) {
            $card->featured_image =
                $this->uploadImage(
                    'gcs',
                    'answers',
                    $data['featured_image']
                );
        }

        $card->save();

        return $card;
    }
}