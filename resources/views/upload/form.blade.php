<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-base-200 px-4">
        <div class="bg-base-100 p-8 rounded-lg shadow-lg w-full max-w-xl text-white">
            <h1 class="text-2xl font-bold mb-6 text-center text-primary">📸 Déposer vos photos</h1>

            @if(session('success'))
                <p class="text-green-400 text-center mb-4">{{ session('success') }}</p>
            @endif

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="photos" class="block mb-2 text-sm font-medium text-gray-300">Sélectionner vos images :</label>
                    <input type="file" id="photos" name="photos[]" multiple class="file-input file-input-bordered w-full" accept="image/*">
                </div>

                <div id="preview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-full">📤 Envoyer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('photos').addEventListener('change', function(event) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';

            Array.from(event.target.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('w-full', 'h-32', 'object-cover', 'rounded');
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</x-app-layout>
