@props(['label','placeholder','name', 'value', 'dummy_img' => '/storage/dummy_assets/800x1180.png', 'cols' => 0])
<div  x-data="{ 
    files: null, 
    handleFileChange(event) {
    const fileList = event.target.files;

    if (fileList && fileList.length > 0) {
        const selectedFile = fileList[0];
        const tempUrl = URL.createObjectURL(selectedFile);
        
        const imgElement =  document.getElementById('img-{{ $name }}')
        imgElement.src = tempUrl;
        console.log(tempUrl)
    }
    },
    clearFiles() { 
        this.files = null; 
        this.$refs.fileInput.value = '';
    } 
}" class="flex items-center flex-col p-4 justify-around rounded-2xl border border-gray-200 col-span-{{ $cols }}" >
    <label for="{{ $name }}" class="mb-1.5 w-full cursor-pointer font-bold ">
            {{ $label }}
        <div  class="w-full bg-gray-200 flex items-center justify-center p-4 mb-2 rounded ">
            <img  id="img-{{ $name }}" src="{{ $value ? $value : $dummy_img }}" alt="" class="h-32 w-fit object-scale-down mb-3">
        </div>
        <input type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            x-ref='fileInput'
            @change="handleFileChange($event)"
            {{ 
                $attributes->merge(['class'=>'hidden']) 
            }} 
            />
    </label>
    <x-v2.ui.button size="sm" onclick="document.getElementById('{{ $name }}').click()" variant="outline" > Upload {{ $label }} </x-v2.ui.button>
</div>
@if ($errors->get($name))
    <ul {{ $attributes->merge(['class' => 'font-bold space-y-1 mt-2 text-sm text-red-600 dark:text-red-500']) }} >
        @foreach ((array) $errors->get($name) as $error)
            <li><p class="text-theme-xs text-error-500">{{ $error }}</p></li>
        @endforeach
    </ul>
@endif