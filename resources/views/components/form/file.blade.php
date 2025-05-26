@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'help' => '',
    'accept' => '',
    'multiple' => false,
    'maxSize' => '', // e.g., '2MB'
    'disabled' => false,
    'preview' => true // Show image preview for image files
])

<div class="space-y-2" x-data="fileUpload()">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors"
         :class="{ 'border-blue-400 bg-blue-50': isDragOver }"
         @dragover.prevent="isDragOver = true"
         @dragleave.prevent="isDragOver = false"
         @drop.prevent="handleDrop($event)">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            
            <div class="flex text-sm text-gray-600">
                <label for="{{ $name }}" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <span>Upload {{ $multiple ? 'files' : 'a file' }}</span>
                    <input 
                        id="{{ $name }}"
                        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
                        type="file"
                        class="sr-only"
                        @if($accept) accept="{{ $accept }}" @endif
                        @if($multiple) multiple @endif
                        @if($required) required @endif
                        @if($disabled) disabled @endif
                        @change="handleFileSelect($event)"
                    >
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            
            @if($accept)
                <p class="text-xs text-gray-500">{{ $accept }}</p>
            @endif
            
            @if($maxSize)
                <p class="text-xs text-gray-500">Max size: {{ $maxSize }}</p>
            @endif
        </div>
    </div>
    
    <!-- File Preview -->
    <div x-show="files.length > 0" class="space-y-2">
        <template x-for="(file, index) in files" :key="index">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <!-- Image Preview -->
                    <template x-if="preview && file.type.startsWith('image/')">
                        <img :src="file.url" :alt="file.name" class="h-10 w-10 object-cover rounded">
                    </template>
                    
                    <!-- File Icon -->
                    <template x-if="!preview || !file.type.startsWith('image/')">
                        <div class="h-10 w-10 flex items-center justify-center bg-blue-100 rounded">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </template>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-900" x-text="file.name"></p>
                        <p class="text-sm text-gray-500" x-text="formatFileSize(file.size)"></p>
                    </div>
                </div>
                
                <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </template>
    </div>
    
    @if($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<script>
function fileUpload() {
    return {
        files: [],
        isDragOver: false,
        
        handleFileSelect(event) {
            this.processFiles(event.target.files);
        },
        
        handleDrop(event) {
            this.isDragOver = false;
            this.processFiles(event.dataTransfer.files);
        },
        
        processFiles(fileList) {
            const filesArray = Array.from(fileList);
            
            filesArray.forEach(file => {
                const fileObj = {
                    name: file.name,
                    size: file.size,
                    type: file.type,
                    file: file
                };
                
                if (file.type.startsWith('image/')) {
                    fileObj.url = URL.createObjectURL(file);
                }
                
                this.files.push(fileObj);
            });
        },
        
        removeFile(index) {
            const file = this.files[index];
            if (file.url) {
                URL.revokeObjectURL(file.url);
            }
            this.files.splice(index, 1);
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }
}
</script>
