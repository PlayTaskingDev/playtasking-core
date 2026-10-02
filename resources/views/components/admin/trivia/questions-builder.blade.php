@props([
    'questions' => collect(),
])

@php
    $questionsData = $questions->map(function ($question) {
        $correctAnswer = null;

        $answers = $question->answers
            ->values()
            ->map(function ($answer, $index) use (&$correctAnswer) {

                if ($answer->is_correct) {
                    $correctAnswer = $index;
                }

                return [
                    'id' => $answer->id,
                    'title' => $answer->title,
                    'featured_image_url' => $answer->featured_image,
                ];
            })
            ->toArray();

        return [
            'id' => $question->id,
            'title' => $question->title,
            'featured_image_url' => $question->featured_image,
            'correct_answer' => $correctAnswer,
            'answers' => $answers,
        ];
    })->values()->toArray();

    /*
     * Si hubo error de validación, recuperamos
     * títulos, IDs, respuestas, etc.
     */
    if (old('questions')) {
        $questionsData = old('questions');
    }
@endphp

<div
    id="trivia-questions-builder"
    class="col-span-2 mt-6"
>

    <div class="mb-5 flex items-center justify-between gap-4">

        <div>
            <h2
                class="text-lg font-semibold
                       text-gray-800 dark:text-white/90"
            >
                {{ __('Questions') }}
            </h2>

            <p
                class="mt-1 text-sm
                       text-gray-500 dark:text-gray-400"
            >
                Agrega todas las preguntas y respuestas
                de la trivia desde esta misma pantalla.
            </p>
        </div>

        <button
            type="button"
            id="add-question-btn"
            class="
                rounded-lg bg-brand-500
                px-4 py-2.5
                text-sm font-medium text-white
                hover:bg-brand-600
            "
        >
            + {{ __('Add Question') }}
        </button>

    </div>

    @if ($errors->get('questions.*'))
        <div
            class="mb-5 rounded-lg
                   bg-red-50 p-4
                   text-sm text-red-700"
        >
            <strong>
                Hay errores en las preguntas o respuestas.
            </strong>

            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->get('questions.*') as $messages)
                    @foreach ((array) $messages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif

    <div
        id="questions-container"
        class="space-y-6"
    ></div>

    <div
        id="no-questions-message"
        class="rounded-xl border border-dashed
               border-gray-300 p-8 text-center
               text-sm text-gray-500
               dark:border-gray-700 dark:text-gray-400"
    >
        Todavía no has agregado preguntas.
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const container =
        document.getElementById('questions-container');

    const addQuestionButton =
        document.getElementById('add-question-btn');

    const emptyMessage =
        document.getElementById('no-questions-message');

    /*
     * Datos existentes provenientes de Laravel.
     */
    const initialQuestions =
        @json($questionsData);

    let nextQuestionIndex = 0;


    function escapeHtml(value = '') {

        const div =
            document.createElement('div');

        div.textContent = value ?? '';

        return div.innerHTML;
    }


    function refreshEmptyMessage() {

        const visibleQuestions =
            container.querySelectorAll(
                '.trivia-question:not([data-deleted="1"])'
            );

        emptyMessage.classList.toggle(
            'hidden',
            visibleQuestions.length > 0
        );
    }


    function addQuestion(data = {}) {

        const questionIndex =
            nextQuestionIndex++;

        const question = document.createElement('div');

        question.className =
            'trivia-question rounded-2xl border ' +
            'border-gray-200 bg-gray-50 p-5 ' +
            'dark:border-gray-700 dark:bg-gray-800/50';

        question.dataset.questionIndex =
            questionIndex;

        question.dataset.existing =
            data.id ? '1' : '0';

        question.dataset.deleted = '0';

        question.innerHTML = `
            <input
                type="hidden"
                name="questions[${questionIndex}][id]"
                value="${escapeHtml(data.id ?? '')}"
            >

            <input
                type="hidden"
                class="question-delete-input"
                name="questions[${questionIndex}][_delete]"
                value="0"
            >

            <div
                class="mb-5 flex items-center
                       justify-between gap-4"
            >

                <h3
                    class="question-number
                           font-semibold text-gray-800
                           dark:text-white"
                >
                    Pregunta
                </h3>

                <button
                    type="button"
                    class="remove-question-btn
                           text-sm font-medium
                           text-red-600 hover:underline"
                >
                    Eliminar pregunta
                </button>

            </div>

            <div class="grid grid-cols-1 gap-5">

                <div>

                    <label
                        class="mb-1.5 block
                               text-sm font-medium
                               text-gray-700
                               dark:text-gray-300"
                    >
                        Pregunta
                    </label>

                    <input
                        type="text"
                        name="questions[${questionIndex}][title]"
                        value="${escapeHtml(data.title ?? '')}"
                        class="
                            block w-full rounded-lg
                            border border-gray-300
                            bg-white px-3 py-2.5
                            text-sm text-gray-900
                            focus:border-brand-500
                            focus:ring-brand-500
                            dark:border-gray-600
                            dark:bg-gray-700
                            dark:text-white
                        "
                    >

                </div>

                <div>

                    <label
                        class="mb-1.5 block
                               text-sm font-medium
                               text-gray-700
                               dark:text-gray-300"
                    >
                        Imagen de la pregunta
                        <span class="text-gray-400">
                            (opcional)
                        </span>
                    </label>

                    ${
                        data.featured_image_url
                            ? `
                                <div class="mb-3">
                                    <img
                                        src="${escapeHtml(data.featured_image_url)}"
                                        class="max-h-40 rounded-lg"
                                        alt=""
                                    >
                                </div>
                            `
                            : ''
                    }

                    <input
                        type="file"
                        name="questions[${questionIndex}][featured_image]"
                        accept=".jpg,.jpeg,.png"
                        class="
                            block w-full rounded-lg
                            border border-gray-300
                            bg-white text-sm
                            text-gray-900
                            dark:border-gray-600
                            dark:bg-gray-700
                            dark:text-gray-300
                        "
                    >

                </div>

            </div>


            <div
                class="mt-6 border-t
                       border-gray-200 pt-5
                       dark:border-gray-700"
            >

                <div
                    class="mb-4 flex items-center
                           justify-between gap-3"
                >

                    <div>
                        <h4
                            class="font-medium
                                   text-gray-800
                                   dark:text-white"
                        >
                            Respuestas
                        </h4>

                        <p
                            class="text-xs
                                   text-gray-500
                                   dark:text-gray-400"
                        >
                            Selecciona con el círculo
                            cuál es la respuesta correcta.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="
                            add-answer-btn
                            rounded-lg border
                            border-gray-300
                            bg-white px-3 py-2
                            text-sm font-medium
                            text-gray-700
                            hover:bg-gray-50
                            dark:border-gray-600
                            dark:bg-gray-700
                            dark:text-gray-200
                        "
                    >
                        + Respuesta
                    </button>

                </div>

                <div
                    class="answers-container space-y-3"
                ></div>

            </div>
        `;


        container.appendChild(
            question
        );


        const answersContainer =
            question.querySelector(
                '.answers-container'
            );

        let nextAnswerIndex = 0;


        function addAnswer(answerData = {}) {

            const answerIndex =
                nextAnswerIndex++;

            const answer =
                document.createElement('div');

            answer.className =
                'trivia-answer rounded-xl border ' +
                'border-gray-200 bg-white p-4 ' +
                'dark:border-gray-700 dark:bg-gray-800';

            answer.dataset.answerIndex =
                answerIndex;

            answer.dataset.existing =
                answerData.id ? '1' : '0';

            answer.dataset.deleted = '0';

            const isCorrect =
                String(data.correct_answer)
                === String(answerIndex);

            answer.innerHTML = `

                <input
                    type="hidden"
                    name="questions[${questionIndex}][answers][${answerIndex}][id]"
                    value="${escapeHtml(answerData.id ?? '')}"
                >

                <input
                    type="hidden"
                    class="answer-delete-input"
                    name="questions[${questionIndex}][answers][${answerIndex}][_delete]"
                    value="0"
                >

                <div
                    class="flex items-start gap-3"
                >

                    <div class="pt-3">

                        <input
                            type="radio"
                            name="questions[${questionIndex}][correct_answer]"
                            value="${answerIndex}"
                            ${isCorrect ? 'checked' : ''}
                            class="
                                h-4 w-4
                                border-gray-300
                                text-brand-600
                                focus:ring-brand-500
                            "
                            title="Respuesta correcta"
                        >

                    </div>

                    <div
                        class="grid flex-1
                               grid-cols-1 gap-3
                               lg:grid-cols-2"
                    >

                        <div>

                            <label
                                class="mb-1 block
                                       text-xs font-medium
                                       text-gray-600
                                       dark:text-gray-400"
                            >
                                Respuesta
                            </label>

                            <input
                                type="text"
                                name="questions[${questionIndex}][answers][${answerIndex}][title]"
                                value="${escapeHtml(answerData.title ?? '')}"
                                class="
                                    block w-full rounded-lg
                                    border border-gray-300
                                    px-3 py-2
                                    text-sm
                                    dark:border-gray-600
                                    dark:bg-gray-700
                                    dark:text-white
                                "
                            >

                        </div>

                        <div>

                            <label
                                class="mb-1 block
                                       text-xs font-medium
                                       text-gray-600
                                       dark:text-gray-400"
                            >
                                Imagen
                                <span class="text-gray-400">
                                    (opcional)
                                </span>
                            </label>

                            ${
                                answerData.featured_image_url
                                    ? `
                                        <img
                                            src="${escapeHtml(answerData.featured_image_url)}"
                                            class="mb-2 max-h-16 rounded"
                                            alt=""
                                        >
                                    `
                                    : ''
                            }

                            <input
                                type="file"
                                name="questions[${questionIndex}][answers][${answerIndex}][featured_image]"
                                accept=".jpg,.jpeg,.png"
                                class="
                                    block w-full text-xs
                                    text-gray-500
                                "
                            >

                        </div>

                    </div>

                    <button
                        type="button"
                        class="
                            remove-answer-btn
                            mt-2
                            text-sm font-medium
                            text-red-600
                            hover:underline
                        "
                    >
                        Eliminar
                    </button>

                </div>
            `;


            answersContainer.appendChild(
                answer
            );


            answer
                .querySelector(
                    '.remove-answer-btn'
                )
                .addEventListener(
                    'click',
                    function () {

                        if (
                            answer.dataset.existing
                            === '1'
                        ) {

                            answer.dataset.deleted =
                                '1';

                            answer
                                .querySelector(
                                    '.answer-delete-input'
                                )
                                .value = '1';

                            /*
                             * Si era la correcta,
                             * quitamos la selección.
                             */
                            const radio =
                                answer.querySelector(
                                    'input[type="radio"]'
                                );

                            if (radio.checked) {
                                radio.checked = false;
                            }

                            answer.classList.add(
                                'hidden'
                            );

                        } else {

                            answer.remove();

                        }
                    }
                );
        }


        question
            .querySelector('.add-answer-btn')
            .addEventListener(
                'click',
                function () {
                    addAnswer();
                }
            );


        question
            .querySelector(
                '.remove-question-btn'
            )
            .addEventListener(
                'click',
                function () {

                    if (
                        question.dataset.existing
                        === '1'
                    ) {

                        question.dataset.deleted =
                            '1';

                        question
                            .querySelector(
                                '.question-delete-input'
                            )
                            .value = '1';

                        question.classList.add(
                            'hidden'
                        );

                    } else {

                        question.remove();

                    }

                    refreshEmptyMessage();
                }
            );


        /*
         * Respuestas provenientes de BD / old().
         */
        if (
            Array.isArray(data.answers)
            &&
            data.answers.length
        ) {

            data.answers.forEach(
                function (answer) {
                    addAnswer(answer);
                }
            );

        } else {

            /*
             * Para una pregunta nueva damos
             * dos respuestas iniciales.
             */
            addAnswer();
            addAnswer();
        }


        refreshQuestionNumbers();
        refreshEmptyMessage();
    }


    function refreshQuestionNumbers() {

        let number = 1;

        container
            .querySelectorAll(
                '.trivia-question'
            )
            .forEach(function (question) {

                if (
                    question.dataset.deleted
                    === '1'
                ) {
                    return;
                }

                question
                    .querySelector(
                        '.question-number'
                    )
                    .textContent =
                        `Pregunta ${number}`;

                number++;
            });
    }


    addQuestionButton.addEventListener(
        'click',
        function () {
            addQuestion();
        }
    );


    /*
     * Cargar preguntas existentes.
     */
    if (
        Array.isArray(initialQuestions)
        &&
        initialQuestions.length
    ) {

        initialQuestions.forEach(
            function (question) {
                addQuestion(question);
            }
        );

    } else {

        /*
         * Una trivia nueva comienza
         * con una pregunta.
         */
        addQuestion();

    }


    refreshQuestionNumbers();
    refreshEmptyMessage();

});
</script>