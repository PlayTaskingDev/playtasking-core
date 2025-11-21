
<x-guest-layout>
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8 bg-white rounded-lg">
                    <h3>Descargar archivo</h3>

                    @if($errors->any())
                        <div style="color:red;">{{ $errors->first() }}</div>
                    @endif
                <form class="max-w-sm mx-auto" method="POST" action="{{ route('file.download',['tenant' => tenant('id')]) }}">
                    <div class="mb-5">
                        <label for="password" class="block mb-2.5 text-sm font-medium text-heading">Contraseña:</label>
                        <input type="password" id="password" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" required />
                    </div>
                    <button type="submit" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Descargar</button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
