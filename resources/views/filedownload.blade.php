
<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 bg-white rounded-lg">
                    <h3>Descargar archivo</h3>

                    @if($errors->any())
                        <div style="color:red;">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('file.download',['tenant' => tenant('id')]) }}">
                        @csrf
                        <label>Contraseña:</label>
                        <input type="password" name="password" required>

                        <button type="submit">Descargar</button>
                    </form>
               
               
                    <h2>Descargar <a href="{{ route('filedownload',['tenant' => tenant('id')]) }}" class="underline">aquí</a></h2>
            </div>
        </div>
    </div>
</x-guest-layout>
