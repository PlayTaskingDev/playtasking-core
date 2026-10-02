@props([
    'cards' => collect(),
])

@php
    /*
     * Cartas existentes.
     */
    $cardsData = $cards
        ->map(function ($card) {
            return [
                'id' => $card->id,
                'name' => $card->name,
                'featured_image_url' => $card->featured_image,
                '_delete' => 0,
            ];
        })
        ->values()
        ->toArray();


    /*
     * Si regresamos por error de validación,
     * recuperamos los campos escritos.
     *
     * Para cartas existentes conservamos también
     * la URL de imagen que ya estaba en BD.
     */
    if (old('cards')) {

        $existingCards = collect($cardsData)
            ->keyBy('id');

        $cardsData = collect(old('cards'))
            ->map(function ($card) use ($existingCards) {

                $existing = !empty($card['id'])
                    ? $existingCards->get($card['id'])
                    : null;

                return array_merge(
                    [
                        'id' => null,
                        'name' => '',
                        'featured_image_url' => null,
                        '_delete' => 0,
                    ],
                    $existing ?? [],
                    $card
                );
            })
            ->values()
            ->toArray();
    }
@endphp


<div
    id="memory-cards-builder"
    class="col-span-2 mt-6"
>

    <div
        class="mb-5 flex flex-wrap
               items-center justify-between gap-4"
    >

        <div>

            <h2
                class="text-lg font-semibold
                       text-gray-800
                       dark:text-white/90"
            >
                {{ __('Memory Cards') }}
            </h2>

            <p
                class="mt-1 text-sm
                       text-gray-500
                       dark:text-gray-400"
            >
                Agrega y administra las cartas
                desde esta misma pantalla.
            </p>

        </div>


        <button
            type="button"
            id="add-memory-card-btn"
            class="
                rounded-lg
                bg-brand-500
                px-4 py-2.5
                text-sm font-medium
                text-white
                hover:bg-brand-600
            "
        >
            + {{ __('Add Card') }}
        </button>

    </div>


    {{-- Errores --}}
    @if ($errors->get('cards.*'))

        <div
            class="mb-5 rounded-lg
                   bg-red-50 p-4
                   text-sm text-red-700"
        >

            <strong>
                Hay errores en las cartas.
            </strong>

            <ul class="mt-2 list-disc pl-5">

                @foreach ($errors->get('cards.*') as $messages)

                    @foreach ((array) $messages as $message)

                        <li>
                            {{ $message }}
                        </li>

                    @endforeach

                @endforeach

            </ul>

        </div>

    @endif


    <div
        id="memory-cards-container"
        class="
            grid grid-cols-1
            gap-5
            md:grid-cols-2
            xl:grid-cols-3
        "
    ></div>


    <div
        id="no-memory-cards-message"
        class="
            rounded-xl
            border border-dashed
            border-gray-300
            p-8
            text-center
            text-sm
            text-gray-500
            dark:border-gray-700
            dark:text-gray-400
        "
    >
        Todavía no has agregado cartas.
    </div>

</div>


<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const container =
            document.getElementById(
                'memory-cards-container'
            );

        const addButton =
            document.getElementById(
                'add-memory-card-btn'
            );

        const emptyMessage =
            document.getElementById(
                'no-memory-cards-message'
            );

        const initialCards =
            @json($cardsData);

        let nextCardIndex = 0;


        function escapeHtml(value = '') {

            const div =
                document.createElement('div');

            div.textContent =
                value ?? '';

            return div.innerHTML;
        }


        function refreshEmptyMessage() {

            const visibleCards =
                container.querySelectorAll(
                    '.memory-card:not([data-deleted="1"])'
                );

            emptyMessage
                .classList
                .toggle(
                    'hidden',
                    visibleCards.length > 0
                );
        }


        function refreshCardNumbers() {

            let number = 1;

            container
                .querySelectorAll(
                    '.memory-card'
                )
                .forEach(function (card) {

                    if (
                        card.dataset.deleted
                        === '1'
                    ) {
                        return;
                    }

                    const title =
                        card.querySelector(
                            '.memory-card-number'
                        );

                    if (title) {
                        title.textContent =
                            `Carta ${number}`;
                    }

                    number++;
                });
        }


        function addCard(data = {}) {

            const cardIndex =
                nextCardIndex++;

            const card =
                document.createElement(
                    'div'
                );

            card.className =
                'memory-card ' +
                'rounded-2xl border ' +
                'border-gray-200 ' +
                'bg-gray-50 p-5 ' +
                'dark:border-gray-700 ' +
                'dark:bg-gray-800/50';

            card.dataset.cardIndex =
                cardIndex;

            card.dataset.existing =
                data.id ? '1' : '0';

            card.dataset.deleted =
                '0';


            card.innerHTML = `

                <input
                    type="hidden"
                    name="cards[${cardIndex}][id]"
                    value="${escapeHtml(data.id ?? '')}"
                >

                <input
                    type="hidden"
                    class="memory-card-delete-input"
                    name="cards[${cardIndex}][_delete]"
                    value="0"
                >


                <div
                    class="
                        mb-4
                        flex items-center
                        justify-between
                        gap-3
                    "
                >

                    <h3
                        class="
                            memory-card-number
                            font-semibold
                            text-gray-800
                            dark:text-white
                        "
                    >
                        Carta
                    </h3>


                    <button
                        type="button"
                        class="
                            remove-memory-card-btn
                            text-sm
                            font-medium
                            text-red-600
                            hover:underline
                        "
                    >
                        Eliminar
                    </button>

                </div>


                ${
                    data.featured_image_url
                        ? `
                            <div
                                class="
                                    mb-4
                                    flex justify-center
                                "
                            >
                                <img
                                    src="${escapeHtml(data.featured_image_url)}"
                                    alt=""
                                    class="
                                        max-h-48
                                        rounded-lg
                                        object-contain
                                    "
                                >
                            </div>
                        `
                        : ''
                }


                <div class="space-y-4">

                    <div>

                        <label
                            class="
                                mb-1.5 block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            "
                        >
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="cards[${cardIndex}][name]"
                            value="${escapeHtml(data.name ?? '')}"
                            placeholder="producto-1"
                            class="
                                block w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                px-3 py-2.5
                                text-sm
                                text-gray-900
                                focus:border-brand-500
                                focus:ring-brand-500
                                dark:border-gray-600
                                dark:bg-gray-700
                                dark:text-white
                            "
                        >

                        <p
                            class="
                                mt-1 text-xs
                                text-gray-400
                            "
                        >
                            Utiliza minúsculas,
                            números y guiones.
                        </p>

                    </div>


                    <div>

                        <label
                            class="
                                mb-1.5 block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                            "
                        >
                            ${
                                data.featured_image_url
                                    ? 'Cambiar imagen'
                                    : 'Imagen'
                            }
                        </label>


                        <input
                            type="file"
                            name="cards[${cardIndex}][featured_image]"
                            accept=".jpg,.jpeg,.png"
                            class="
                                block w-full
                                rounded-lg
                                border
                                border-gray-300
                                bg-white
                                text-sm
                                text-gray-900
                                dark:border-gray-600
                                dark:bg-gray-700
                                dark:text-gray-300
                            "
                        >

                    </div>

                </div>
            `;


            container.appendChild(
                card
            );


            card
                .querySelector(
                    '.remove-memory-card-btn'
                )
                .addEventListener(
                    'click',
                    function () {

                        if (
                            card.dataset.existing
                            === '1'
                        ) {

                            card.dataset.deleted =
                                '1';

                            card
                                .querySelector(
                                    '.memory-card-delete-input'
                                )
                                .value = '1';

                            card.classList.add(
                                'hidden'
                            );

                        } else {

                            card.remove();

                        }


                        refreshCardNumbers();
                        refreshEmptyMessage();
                    }
                );


            refreshCardNumbers();
            refreshEmptyMessage();
        }


        addButton.addEventListener(
            'click',
            function () {
                addCard();
            }
        );


        /*
         * Cargar cartas existentes.
         */
        if (
            Array.isArray(initialCards)
            &&
            initialCards.length
        ) {

            initialCards.forEach(
                function (card) {

                    /*
                     * Si venimos de old() con una
                     * carta marcada para eliminar,
                     * conservamos el estado.
                     */
                    addCard(card);

                    if (
                        String(card._delete)
                        === '1'
                    ) {

                        const addedCard =
                            container.lastElementChild;

                        addedCard.dataset.deleted =
                            '1';

                        addedCard
                            .querySelector(
                                '.memory-card-delete-input'
                            )
                            .value = '1';

                        addedCard.classList.add(
                            'hidden'
                        );
                    }
                }
            );

        } else {

            /*
             * Para un Memory nuevo ponemos
             * dos cartas iniciales.
             */
            addCard();
            addCard();

        }


        refreshCardNumbers();
        refreshEmptyMessage();

    }
);
</script>