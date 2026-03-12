@props(['label','placeholder','name', 'value' => null, 'cols' => 0, 'iddatepicker' ])

{{-- <div class="formfield">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="datetime-local" name="{{ $name }}" id="{{ $name }}" value="{{ $value ?? now()->format('Y-m-d\TH:i') }}"
        {{ 
            $attributes->merge([
            'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
            ]) 
        }}
    />
    @if ($errors->get($name))
            <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
                @foreach ((array) $errors->get($name) as $error)
                    <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
                @endforeach
            </ul>
        @endif
  </div> --}}

  
<div class="input-group flex flex-col col-span-{{ $cols }} gap-2.5 mb-6">
    <button type="button" data-modal-target="timepicker-modal-{{ $name }}" data-modal-toggle="timepicker-modal-{{ $name }}" class="inline-flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
    <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
    {{ $label }}
    </button>
    <input type="text" id="{{ $name }}" name="{{ $name }}" value="{{ $value ?? '' }}" 
         {{ 
                $attributes->merge(['class'=>'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30']) 
            }}
    />
    @if ($errors->get($name))
        <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
            @foreach ((array) $errors->get($name) as $error)
                <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
            @endforeach
        </ul>
    @endif
</div>
<!-- Main modal -->
<div id="timepicker-modal-{{ $name }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-92 max-h-full">
        <!-- Modal content -->
        <div class="relative bg-neutral-primary-soft rounded-base">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 border-b rounded-t border-default">
                <h3 class="font-medium text-heading">
                    {{ $label }}
                </h3>
               <button type="button" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center" data-modal-toggle="timepicker-modal-{{ $name }}">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 pt-0">
                <div id="datepicker-{{ $name }}" inline-datepicker datepicker-autoselect-today class="mx-auto sm:mx-0 flex justify-center my-5 [&>div>div]:shadow-none [&>div>div]:bg-neutral-secondary-soft [&_div>button]:bg-neutral-secondary-soft"></div>
                <label class="text-sm font-medium text-heading mb-2 block">
                Pick your time
                </label>
                <ul id="timetable" class="grid w-full grid-cols-3 gap-2 mb-5">
                    <li>
                        <input type="radio" id="00:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="00:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            00:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="01:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="01:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            01:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="02:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="02:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            02:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="03:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="03:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            03:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="05:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}" checked>
                        <label for="05:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            04:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="12-30-pm-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="12-30-pm-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            05:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="06:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="06:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            06:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="07:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="07:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            07:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="08:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="08:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            08:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="09:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="09:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            09:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="10:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="10:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            10:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="11:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="11:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            11:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="12:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="12:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            12:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="13:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="13:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            13:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="14:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="14:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            14:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="15:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="15:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            15:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="16:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="16:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            16:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="17:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="17:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            17:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="18:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="18:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            18:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="19:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="19:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            19:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="20:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="20:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            20:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="21:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="21:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            21:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="22:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="22:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            22:00
                        </label>
                    </li>
                    <li>
                        <input type="radio" id="23:00-{{ $name }}" value="" class="hidden peer" name="timetable-{{ $name }}">
                        <label for="23:00-{{ $name }}"
                        class="inline-flex items-center justify-center w-full p-2 text-sm font-medium text-center bg-neutral-primary-soft border rounded-base cursor-pointer text-fg-brand border-brand peer-checked:border-brand peer-checked:bg-brand hover:text-white peer-checked:text-white hover:bg-brand-strong">
                            23:00
                        </label>
                    </li>
                </ul>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" class="text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" data-modal-hide="timepicker-modal-{{ $name }}" data-target-calendar="{{ $name }}" onclick="saveDateTimePicker(this)">Save</button>
                    <button type="button" data-modal-hide="timepicker-modal-{{ $name }}" class="text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Discard</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script>
        function setDateTimeValue(date, time) {
        const inputElement = document.getElementById('{{ $name }}');
        
        if (!inputElement) {
            console.error('Input element not found');
            return;
        }
        
        // Convert date string (YYYY-MM-DD) and time string (HH:MM) to datetime format
        if (date && time) {
            const dateTimeString = `${date} ${time}`;
            inputElement.value = dateTimeString;
            inputElement.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
    
    function setDateTimeFromPicker() {
        // Get the selected date from the datepicker
        const datepickerElement = document.querySelector('[inline-datepicker]');
        const dateInput = datepickerElement.querySelector('input[type="date"]');
        const selectedDate = dateInput ? dateInput.value : null;
        
        // Get the selected time from the timetable
        const selectedTimeRadio = document.querySelector('input[name="timetable-{{ $name }}"]:checked');
        const selectedTimeLabel = selectedTimeRadio ? selectedTimeRadio.nextElementSibling.textContent.trim() : null;
        
        // Set the value in the main input
        const inputElement = document.getElementById('{{ $name }}');
        if (selectedDate && selectedTimeLabel) {
            const dateTimeString = `${selectedDate} ${selectedTimeLabel}`;
            inputElement.value = dateTimeString;
            inputElement.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
    
    // Event listener for the Save button
    document.getElementById('save-datetime-btn').addEventListener('click', function() {
        setDateTimeFromPicker();
        // Close the modal after saving
        const modal = document.getElementById('timepicker-modal-{{ $name }}');
        const modalToggleBtn = modal.querySelector('[data-modal-hide="timepicker-modal-{{ $name }}"]');
        if (modalToggleBtn) {
            modalToggleBtn.click();
        }
    });
</script> --}}