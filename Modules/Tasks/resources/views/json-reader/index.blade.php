<x-tasks::layouts.master title="JSON File Reader">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <flux:heading size="lg">JSON File Reader</flux:heading>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- File Input Section -->
            <div class="space-y-4">
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                    <flux:heading size="md" class="mb-4">Read JSON File</flux:heading>
                    
                    <div class="space-y-4">
                        <!-- File Path Input -->
                        <div>
                            <div>
                                <label for="filePath" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Path</label>
                                <input 
                                    type="text"
                                    id="filePath" 
                                    placeholder="Enter file path (e.g., data.json)" 
                                    value="data.json"
                                    class="block w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Enter the path to your JSON file</p>
                            </div>
                        </div>

                        <button onclick="readJsonFile()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Read JSON File
                        </button>

                        <!-- Available Files -->
                        <div>
                            <button onclick="loadAvailableFiles()" class="w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                Load Available JSON Files
                            </button>
                            <div id="availableFiles" class="mt-2 space-y-1"></div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                    <flux:heading size="md" class="mb-4">Upload JSON File</flux:heading>
                    
                    <form id="uploadForm" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="jsonFile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select JSON File</label>
                            <input 
                                type="file" 
                                id="jsonFile" 
                                name="json_file" 
                                accept=".json,.txt"
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select a JSON file to upload and read</p>
                        </div>
                        
                        <button type="button" onclick="uploadJsonFile()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Upload & Read
                        </button>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div class="space-y-4">
                <!-- Loading Indicator -->
                <div id="loading" class="hidden">
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <span class="ml-2 text-gray-600">Loading...</span>
                        </div>
                    </div>
                </div>

                <!-- Error Display -->
                <div id="error" class="hidden">
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 p-6">
                        <flux:heading size="md" class="mb-2">Error</flux:heading>
                        <div id="errorMessage"></div>
                    </div>
                </div>

                <!-- Success Results -->
                <div id="results" class="hidden space-y-4">
                    <!-- File Info -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                        <flux:heading size="md" class="mb-2">File Information</flux:heading>
                        <div id="fileInfo" class="text-sm text-gray-600"></div>
                    </div>

                    <!-- Structured Data -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                        <flux:heading size="md" class="mb-2">Structured Data</flux:heading>
                        <div id="structuredData" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96"></div>
                    </div>

                    <!-- Raw JSON -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                        <flux:heading size="md" class="mb-2">Raw JSON</flux:heading>
                        <pre id="rawJson" class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg overflow-auto max-h-96 text-sm"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('error').classList.add('hidden');
            document.getElementById('results').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }

        function showError(message) {
            hideLoading();
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('error').classList.remove('hidden');
            document.getElementById('results').classList.add('hidden');
        }

        function showResults(data) {
            hideLoading();
            
            // File info
            document.getElementById('fileInfo').innerHTML = `
                <strong>File Path:</strong> ${data.file_path}<br>
                <strong>Status:</strong> Successfully read
            `;

            // Structured data
            document.getElementById('structuredData').innerHTML = formatStructuredData(data.data);

            // Raw JSON
            document.getElementById('rawJson').textContent = data.pretty_json;

            document.getElementById('error').classList.add('hidden');
            document.getElementById('results').classList.remove('hidden');
        }

        function formatStructuredData(data, indent = 0) {
            const spaces = '  '.repeat(indent);
            let html = '';

            if (Array.isArray(data)) {
                html += `${spaces}[\n`;
                data.forEach((item, index) => {
                    html += `${spaces}  [${index}]: `;
                    if (typeof item === 'object') {
                        html += `\n${formatStructuredData(item, indent + 2)}`;
                    } else {
                        html += `${item}\n`;
                    }
                });
                html += `${spaces}]\n`;
            } else if (typeof data === 'object' && data !== null) {
                for (const [key, value] of Object.entries(data)) {
                    html += `${spaces}<strong>${key}:</strong> `;
                    if (typeof value === 'object') {
                        html += `\n${formatStructuredData(value, indent + 1)}`;
                    } else {
                        html += `${value}<br>\n`;
                    }
                }
            }

            return html;
        }

        function getCsrfToken() {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            if (metaTag) {
                return metaTag.getAttribute('content');
            }
            // Fallback - get from form if meta tag is not available
            const csrfInput = document.querySelector('input[name="_token"]');
            return csrfInput ? csrfInput.value : '{{ csrf_token() }}';
        }

        async function readJsonFile() {
            const filePath = document.getElementById('filePath').value;
            
            if (!filePath.trim()) {
                showError('Please enter a file path');
                return;
            }

            showLoading();

            try {
                const response = await fetch('{{ route("json-reader.read") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({
                        file_path: filePath
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showResults(data);
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('Failed to read JSON file: ' + error.message);
            }
        }

        function selectAndReadFile(filePath) {
            document.getElementById('filePath').value = filePath;
            readJsonFile();
        }

        async function loadAvailableFiles() {
            try {
                const response = await fetch('{{ route("json-reader.files") }}');
                const data = await response.json();

                const container = document.getElementById('availableFiles');
                
                if (data.success && data.files.length > 0) {
                    container.innerHTML = data.files.map(file => 
                        `<button onclick="selectAndReadFile('${file}')" 
                         class="block w-full text-left text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded">
                         ${file}
                         </button>`
                    ).join('');
                } else {
                    container.innerHTML = '<p class="text-sm text-gray-500">No JSON files found</p>';
                }
            } catch (error) {
                console.error('Failed to load available files:', error);
            }
        }

        async function uploadJsonFile() {
            const fileInput = document.getElementById('jsonFile');
            const file = fileInput.files[0];

            if (!file) {
                showError('Please select a file to upload');
                return;
            }

            showLoading();

            const formData = new FormData();
            formData.append('json_file', file);

            try {
                const response = await fetch('{{ route("json-reader.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showResults(data);
                } else {
                    showError(data.message);
                }
            } catch (error) {
                showError('Failed to upload and read file: ' + error.message);
            }
        }
    </script>
</x-tasks::layouts.master>