<x-admin-layout>
  <x-slot name="header">
      <div class="flex justify-between items-center w-full">
          <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
              {{ __('Cài đặt Ảnh Nền Trang Chủ') }}
          </h2>
          <button type="button" onclick="document.getElementById('settings-form').submit()" class="btn btn-primary btn-sm btn-save-settings" disabled>Lưu cài đặt mờ / trong suốt</button>
      </div>
  </x-slot>

  <section class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <form method="POST" action="{{ route('admin.settings.background.update') }}" id="settings-form" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
          @csrf
          <input type="hidden" name="bg_zone1_blur"    id="zone1-blur-hidden"    value="{{ $settings['bg_zone1_blur'] }}">
          <input type="hidden" name="bg_zone1_opacity" id="zone1-opacity-hidden" value="{{ $settings['bg_zone1_opacity'] }}">
          <input type="hidden" name="bg_zone2_blur"    id="zone2-blur-hidden"    value="{{ $settings['bg_zone2_blur'] }}">
          <input type="hidden" name="bg_zone2_opacity" id="zone2-opacity-hidden" value="{{ $settings['bg_zone2_opacity'] }}">


        <!-- == ZONE 1 == -->
        <h2 class="text-xl font-bold mb-4">Zone 1 – Ảnh nền phần trên (Hero Section)</h2>

        <div class="flex flex-col md:flex-row gap-8">
          <!-- Left: Controls -->
          <div class="flex-1 space-y-4">

            <!-- Image upload -->
            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Ảnh nền Zone 1</label>
                
                <label for="zone1-image-input" id="zone1-dropzone" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700/50 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 transition-all duration-200 group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-1 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Nhấn để chọn ảnh</span> hoặc kéo thả vào đây</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Hỗ trợ định dạng: PNG, JPG, WEBP, GIF</p>
                    </div>
                    <input id="zone1-image-input" type="file" class="hidden" accept="image/*" />
                </label>

                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        <p id="zone1-file-name" class="text-sm text-gray-600 dark:text-gray-300 truncate">
                            {{ $settings['bg_zone1_image'] ? basename($settings['bg_zone1_image']) : 'Chưa có ảnh được chọn' }}
                        </p>
                    </div>
                    <div class="flex gap-2 ml-2">
                        <button id="zone1-upload-btn" class="btn btn-sm btn-primary shadow-sm" disabled>Tải lên</button>
                        <button type="button" id="zone1-delete-btn" class="btn btn-sm btn-error shadow-sm {{ empty($settings['bg_zone1_image']) ? 'hidden' : '' }}">Xóa ảnh</button>
                    </div>
                </div>
                <p id="zone1-current-path" class="hidden">{{ $settings['bg_zone1_image'] ?? '' }}</p>
            </div>

            <!-- Blur slider -->
            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Độ mờ (Blur): <span id="zone1-blur-value">{{ $settings['bg_zone1_blur'] }}</span>px</label>
                <input type="range" id="zone1-blur-slider" min="0" max="30" value="{{ $settings['bg_zone1_blur'] }}" class="range mt-1 w-full max-w-xs">
            </div>

            <!-- Opacity slider -->
            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Độ trong suốt (Opacity): <span id="zone1-opacity-value">{{ $settings['bg_zone1_opacity'] }}</span></label>
                <input type="range" id="zone1-opacity-slider" min="0" max="1" step="0.05" value="{{ $settings['bg_zone1_opacity'] }}" class="range mt-1 w-full max-w-xs">
            </div>

          </div>

          <!-- Right: Live Preview -->
          <div class="flex-1">
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Xem trước Zone 1</label>
            <div id="zone1-preview" class="w-full h-64 rounded-lg overflow-hidden relative bg-gray-200 dark:bg-gray-700">
                <div id="zone1-preview-inner"
                     class="absolute -inset-10 bg-cover bg-center transition-all"
                     style="
                         background-image: url('{{ $settings['bg_zone1_image'] ? asset($settings['bg_zone1_image']) : '' }}');
                         filter: blur({{ $settings['bg_zone1_blur'] ?? 0 }}px);
                         opacity: {{ $settings['bg_zone1_opacity'] ?? 0.5 }};
                     ">
                </div>
            </div>
          </div>
        </div>

        <hr class="my-8 border-gray-200 dark:border-gray-700">

        <!-- == ZONE 2 == -->
        <h2 class="text-xl font-bold mb-4">Zone 2 – Ảnh nền lặp lại (phần bên dưới)</h2>
        
        <div class="flex flex-col md:flex-row gap-8">
          <!-- Left: Controls -->
          <div class="flex-1 space-y-4">

            <!-- Image upload -->
            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Ảnh nền Zone 2</label>
                
                <label for="zone2-image-input" id="zone2-dropzone" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700/50 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 transition-all duration-200 group">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                        </svg>
                        <p class="mb-1 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold text-indigo-600 dark:text-indigo-400">Nhấn để chọn ảnh</span> hoặc kéo thả vào đây</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Hỗ trợ định dạng: PNG, JPG, WEBP, GIF</p>
                    </div>
                    <input id="zone2-image-input" type="file" class="hidden" accept="image/*" />
                </label>

                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        <p id="zone2-file-name" class="text-sm text-gray-600 dark:text-gray-300 truncate">
                            {{ $settings['bg_zone2_image'] ? basename($settings['bg_zone2_image']) : 'Chưa có ảnh được chọn' }}
                        </p>
                    </div>
                    <div class="flex gap-2 ml-2">
                        <button id="zone2-upload-btn" class="btn btn-sm btn-primary shadow-sm" disabled>Tải lên</button>
                        <button type="button" id="zone2-delete-btn" class="btn btn-sm btn-error shadow-sm {{ empty($settings['bg_zone2_image']) ? 'hidden' : '' }}">Xóa ảnh</button>
                    </div>
                </div>
                <p id="zone2-current-path" class="hidden">{{ $settings['bg_zone2_image'] ?? '' }}</p>
            </div>

            <!-- Blur slider -->
            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Độ mờ (Blur): <span id="zone2-blur-value">{{ $settings['bg_zone2_blur'] }}</span>px</label>
                <input type="range" id="zone2-blur-slider" min="0" max="30" value="{{ $settings['bg_zone2_blur'] }}" class="range mt-1 w-full max-w-xs">
            </div>

            <!-- Opacity slider -->
            <div>
                <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Độ trong suốt (Opacity): <span id="zone2-opacity-value">{{ $settings['bg_zone2_opacity'] }}</span></label>
                <input type="range" id="zone2-opacity-slider" min="0" max="1" step="0.05" value="{{ $settings['bg_zone2_opacity'] }}" class="range mt-1 w-full max-w-xs">
            </div>

          </div>

          <!-- Right: Live Preview -->
          <div class="flex-1">
            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1">Xem trước Zone 2</label>
            <div id="zone2-preview" class="w-full h-64 rounded-lg overflow-hidden relative bg-gray-200 dark:bg-gray-700">
                <div id="zone2-preview-inner"
                     class="absolute -inset-10 bg-cover bg-center transition-all"
                     style="
                         background-image: url('{{ $settings['bg_zone2_image'] ? asset($settings['bg_zone2_image']) : '' }}');
                         filter: blur({{ $settings['bg_zone2_blur'] ?? 0 }}px);
                         opacity: {{ $settings['bg_zone2_opacity'] ?? 0.5 }};
                     ">
                </div>
            </div>
          </div>
        </div>

        <hr class="my-8 border-gray-200 dark:border-gray-700">

        <!-- Save button -->
        <div class="flex justify-end">
          <button type="submit" class="btn btn-primary btn-save-settings" disabled>Lưu cài đặt mờ / trong suốt</button>
        </div>

      </form>
    </div>
  </section>

  <x-slot name="script">
    <script>
    function setupZonePreview(zoneNumber) {
        const n = zoneNumber; // 1 or 2
        const blurSlider    = document.getElementById(`zone${n}-blur-slider`);
        const opacSlider    = document.getElementById(`zone${n}-opacity-slider`);
        const blurValue     = document.getElementById(`zone${n}-blur-value`);
        const opacValue     = document.getElementById(`zone${n}-opacity-value`);
        const blurHidden    = document.getElementById(`zone${n}-blur-hidden`);
        const opacHidden    = document.getElementById(`zone${n}-opacity-hidden`);
        const previewInner  = document.getElementById(`zone${n}-preview-inner`);
        const uploadBtn     = document.getElementById(`zone${n}-upload-btn`);
        const deleteBtn     = document.getElementById(`zone${n}-delete-btn`);
        const fileInput     = document.getElementById(`zone${n}-image-input`);
        const currentPath   = document.getElementById(`zone${n}-current-path`);
        const fileNameElem  = document.getElementById(`zone${n}-file-name`);

        // Handle file selection
        fileInput?.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                if (fileNameElem) fileNameElem.textContent = file.name;
                if (uploadBtn) uploadBtn.disabled = false;
                
                // Update preview instantly
                const previewInner = document.getElementById(`zone${n}-preview-inner`);
                if (previewInner) {
                    previewInner.style.backgroundImage = `url('${URL.createObjectURL(file)}')`;
                }
            } else {
                if (fileNameElem) fileNameElem.textContent = 'Chưa có ảnh được chọn';
                if (uploadBtn) uploadBtn.disabled = true;
            }
        });

        const checkDirtiness = () => {
            document.querySelectorAll('.btn-save-settings').forEach(btn => btn.disabled = false);
        };

        // Blur slider
        blurSlider?.addEventListener('input', function () {
            blurValue.textContent = this.value;
            blurHidden.value = this.value;
            if (previewInner) previewInner.style.filter = `blur(${this.value}px)`;
            checkDirtiness();
        });

        // Opacity slider
        opacSlider?.addEventListener('input', function () {
            opacValue.textContent = this.value;
            opacHidden.value = this.value;
            if (previewInner) previewInner.style.opacity = this.value;
            checkDirtiness();
        });

        // Upload button
        uploadBtn?.addEventListener('click', function () {
            const file = fileInput?.files[0];
            if (!file) { showToast('Vui lòng chọn ảnh', 'error'); return; }

            this.disabled = true;
            this.textContent = 'Đang tải...';

            const formData = new FormData();
            formData.append('zone', String(n));
            formData.append('image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('{{ route("admin.settings.background.upload") }}', {
                method: 'POST',
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (previewInner) previewInner.style.backgroundImage = `url('${data.image_url}?t=${Date.now()}')`;
                    if (currentPath) currentPath.textContent = data.image_url;
                    if (fileNameElem) fileNameElem.textContent = data.image_url.split('/').pop();
                    if (deleteBtn) deleteBtn.classList.remove('hidden');
                    showToast(`Tải ảnh Zone ${n} thành công`, 'success');
                } else {
                    showToast('Lỗi: ' + (data.message ?? 'Unknown error'), 'error');
                }
            })
            .catch(() => showToast('Có lỗi kết nối', 'error'))
            .finally(() => {
                this.disabled = true;
                this.textContent = 'Tải lên';
                fileInput.value = '';
            });
        });

        // Delete button
        deleteBtn?.addEventListener('click', function () {
            if (!confirm(`Bạn có chắc chắn muốn xóa ảnh nền Zone ${n}?`)) return;

            this.disabled = true;
            this.textContent = 'Đang xóa...';

            const formData = new FormData();
            formData.append('zone', String(n));
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch('{{ route("admin.settings.background.delete") }}', {
                method: 'POST',
                body: formData,
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (previewInner) previewInner.style.backgroundImage = `none`;
                    if (currentPath) currentPath.textContent = '';
                    if (fileNameElem) fileNameElem.textContent = 'Chưa có ảnh được chọn';
                    deleteBtn.classList.add('hidden');
                    showToast(`Xóa ảnh Zone ${n} thành công`, 'success');
                } else {
                    showToast('Lỗi: ' + (data.message ?? 'Unknown error'), 'error');
                }
            })
            .catch(() => showToast('Có lỗi kết nối', 'error'))
            .finally(() => {
                this.disabled = false;
                this.textContent = 'Xóa ảnh';
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupZonePreview(1);
        setupZonePreview(2);
    });
    </script>
  </x-slot>
</x-admin-layout>
