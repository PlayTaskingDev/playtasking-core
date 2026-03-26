<x-app-layout>
    <x-slot name="title">
        {{ $title }}
    </x-slot>
    <x-slot name="description">
        {{ $description }}
    </x-slot>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quizzes') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- @if (session('status'))
                <x-alert :status="session('status')" class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 mb-4 text-sm rounded-lg"
                    role="alert" />
            @endif --}}
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 content-start dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5">
                @foreach ($quizzes as $quiz)
                    <div
                        class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">
                        @if ($user_quizzes->contains($quiz->id))
                            <img class="rounded-t-lg" src="{{ $quiz->featured_image }}" alt="{{ $quiz->title }}" />
                        @else
                            <a href="{{ route('quiz.show', ['slug' => $quiz->slug]) }}">
                                <img class="rounded-t-lg" src="{{ $quiz->featured_image }}" alt="{{ $quiz->title }}" />
                            </a>
                        @endif
                        <div class="p-5">
                            @if ($user_quizzes->contains($quiz->id))
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $quiz->title }}
                                </h5>
                            @else
                                <a href="{{ route('quiz.show', ['slug' => $quiz->slug]) }}">
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                                        {{ $quiz->title }}
                                    </h5>
                                </a>
                            @endif
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                {{ $quiz->description }}
                            </p>

                            @if ($user_awards->contains($quiz->id))
                                <x-primary-link href="{{ route('dashboard.awards.show', ['award' => $quiz->award]) }}"
                                    class="inline-flex items-center">
                                    {{ __('You win') }}
                                    <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </x-primary-link>
                            @elseif ($user_quizzes->contains($quiz->id))
                                <div
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                    {{ __('Quizzed!') }}
                                </div>
                            @else
                                <x-primary-link href="{{ route('quiz.show', ['slug' => $quiz->slug]) }}"
                                    class="inline-flex items-center">
                                    {{ __('Quiz now') }}
                                    <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                                </x-primary-link>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @section('scripts')
        @if (session('status'))
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    let status = '{{ session('status') }}'
                    switch (status) {
                        case 'quiz_failed':
                            var mTitle = '{{ __('Quiz Failed!') }}';
                            var mMessage =
                                '{{ __('You have not success in this quiz. Keep participating in other quizzes.') }}';
                            break;
                        case 'not_active':
                            var mTitle = '{{ __('Expired Quiz') }}';
                            var mMessage = '{{ __('Sorry, the quiz is not active now.') }}';
                            break;
                        case 'has_quizzed':
                            var mTitle = '{{ __('We got you!') }}';
                            var mMessage = '{{ __('You have been participated in this quiz.') }}';
                            break;
                        default:
                            //
                    }

                    let modalTitle = document.getElementById("modal-title");
                    let modalContent = document.getElementById("modal-content");
                    let modalIcon = document.getElementById("modal-icon");
                    modalContent.innerHTML = '';

                    modalTitle.innerHTML = mTitle;
                    modalIcon.src = '{{ Vite::asset('resources/images/movie-error.png') }}';
                    let paragraph = document.createElement("p");
                    paragraph.innerText = mMessage;

                    modalContent.appendChild(paragraph);
                    modalWindow.show();
                });
            </script>
        @endif
    @endsection
</x-app-layout>
