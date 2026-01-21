@props(['label','placeholder','name', 'value' => '/storage/dummy_assets/600x200.png'])
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
}" class="flex items-center flex-col p-4 justify-around rounded-2xl border border-gray-200" >
    <label for="{{ $name }}" class="mb-1.5 w-full cursor-pointer font-bold ">
            {{ $label }}
        <div  class="w-full bg-gray-200 flex items-center justify-center p-4 mb-2 rounded ">
            <img  id="img-{{ $name }}" src="{{ $value }}" alt="" class="h-32 w-fit object-scale-down mb-3">
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
    <x-v2.ui.button size="sm" variant="outline" > Upload {{ $label }} </x-v2.ui.button>
</div>