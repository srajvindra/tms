<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                ✨ Create New Task
            </h1>
            <p class="text-gray-600 text-lg">Transform your ideas into actionable tasks</p>
        </div>

        <!-- Main Form Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 p-8">
            @if (session()->has('message'))
                <div class="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-xl shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('message') }}
                    </div>
                </div>
            @endif
        
            <form wire:submit="create" class="space-y-8">
                <!-- Task Description Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">📝 Task Details</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- What -->
                        <div>
                            <label for="what" class="block text-sm font-semibold text-gray-700 mb-2">
                                🎯 What (Task Description) <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="what" id="what" rows="3" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-blue-300 bg-white/70 backdrop-blur-sm" 
                                placeholder="Describe what needs to be accomplished..."></textarea>
                            @error('what') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">📋 Basic Information</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Source -->
                        <div>
                            <label for="source" class="block text-sm font-semibold text-gray-700 mb-2">
                                🏠 Source
                            </label>
                            <input type="text" wire:model="source" id="source" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-300 bg-white/70 backdrop-blur-sm" 
                                placeholder="Where did this task come from?">
                            @error('source') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Action -->
                        <div>
                            <label for="action" class="block text-sm font-semibold text-gray-700 mb-2">
                                ⚡ Action
                            </label>
                            <input type="text" wire:model="action" id="action" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-300 bg-white/70 backdrop-blur-sm" 
                                placeholder="What action needs to be taken?">
                            @error('action') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                                🏷️ Type
                            </label>
                            <input type="text" wire:model="type" id="type" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-300 bg-white/70 backdrop-blur-sm" 
                                placeholder="What type of task is this?">
                            @error('type') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                                📂 Category
                            </label>
                            <input type="text" wire:model="category" id="category" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-300 bg-white/70 backdrop-blur-sm" 
                                placeholder="Primary category">
                            @error('category') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Category II -->
                        <div class="md:col-span-2">
                            <label for="category_ii" class="block text-sm font-semibold text-gray-700 mb-2">
                                📂 Category II <span class="text-gray-500 font-normal">(Optional)</span>
                            </label>
                            <input type="text" wire:model="category_ii" id="category_ii" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-green-300 bg-white/70 backdrop-blur-sm" 
                                placeholder="Secondary category (optional)">
                            @error('category_ii') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Priority & Status Section -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">🎨 Priority & Status</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                                🚀 Priority <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model="priority" id="priority" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:border-orange-300 bg-white/70 backdrop-blur-sm appearance-none">
                                    <option value="low">🟢 Low Priority</option>
                                    <option value="medium">🟡 Medium Priority</option>
                                    <option value="high">🟠 High Priority</option>
                                    <option value="urgent">🔴 Urgent</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('priority') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                📊 Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select wire:model="status" id="status" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200 hover:border-orange-300 bg-white/70 backdrop-blur-sm appearance-none">
                                    <option value="pending">⏳ Pending</option>
                                    <option value="in_progress">🔄 In Progress</option>
                                    <option value="completed">✅ Completed</option>
                                    <option value="cancelled">❌ Cancelled</option>
                                    <option value="on_hold">⏸️ On Hold</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('status') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">💭 Additional Notes</h3>
                    </div>
                    
                    <div>
                        <label for="comments" class="block text-sm font-semibold text-gray-700 mb-2">
                            📝 Comments <span class="text-gray-500 font-normal">(Optional)</span>
                        </label>
                        <textarea wire:model="comments" id="comments" rows="4" 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 hover:border-purple-300 bg-white/70 backdrop-blur-sm resize-none" 
                            placeholder="Add any additional notes, context, or requirements..."></textarea>
                        @error('comments') 
                            <div class="mt-1 flex items-center text-red-600 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Recurring Options Section -->
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-xl p-6 border border-cyan-100">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">🔄 Recurring Settings</h3>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Is Recurring Checkbox -->
                        <div class="relative">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" wire:model.live="is_recurring" id="is_recurring" 
                                        class="sr-only">
                                    <div class="w-6 h-6 bg-white border-2 border-gray-300 rounded-md group-hover:border-cyan-400 transition-colors duration-200 flex items-center justify-center"
                                        :class="$wire.is_recurring ? 'bg-cyan-500 border-cyan-500' : ''">
                                        <svg class="w-4 h-4 text-white opacity-0 transition-opacity duration-200" 
                                            :class="$wire.is_recurring ? 'opacity-100' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <span class="ml-3 text-sm font-semibold text-gray-700 group-hover:text-cyan-600 transition-colors duration-200">
                                    🔁 Make this a recurring task
                                </span>
                            </label>
                            @error('is_recurring') 
                                <div class="mt-1 flex items-center text-red-600 text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Recurring Type Field (conditional) -->
                        @if($is_recurring)
                            <div class="animate-fadeIn">
                                <label for="recurring_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    ⏰ Recurring Pattern <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="recurring_type" id="recurring_type" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all duration-200 hover:border-cyan-300 bg-white/70 backdrop-blur-sm" 
                                    placeholder="e.g., daily, weekly, monthly, every 2 weeks...">
                                @error('recurring_type') 
                                    <div class="mt-1 flex items-center text-red-600 text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
            
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-100">
                    <button type="submit" wire:loading.attr="disabled" 
                        class="group relative px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-blue-300 disabled:opacity-50 transition-all duration-200 transform hover:scale-105 disabled:hover:scale-100 shadow-lg hover:shadow-xl">
                        <div class="flex items-center justify-center space-x-2">
                            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <svg wire:loading class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove class="font-semibold">✨ Create Task</span>
                            <span wire:loading class="font-semibold">Creating...</span>
                        </div>
                        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl blur-xl opacity-30 group-hover:opacity-40 transition-opacity"></div>
                    </button>
                    
                    <button type="button" wire:click="resetForm" 
                        class="group relative px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="font-semibold">🔄 Reset Form</span>
                        </div>
                    </button>
                    
                    <button type="button" onclick="window.history.back()" 
                        class="group relative px-8 py-4 bg-white text-gray-700 border-2 border-gray-300 rounded-xl hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="font-semibold">⬅️ Go Back</span>
                        </div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Floating Help Tips -->
        <div class="mt-8 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl p-6 border border-yellow-200 shadow-sm">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0 mt-1">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-yellow-800 mb-1">💡 Pro Tips</h3>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Be specific in your task description for better clarity</li>
                        <li>• Use recurring tasks for regular activities like reviews or maintenance</li>
                        <li>• Set appropriate priority levels to help with task management</li>
                        <li>• Add detailed comments to provide context for future reference</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Custom scrollbar for textarea */
    textarea::-webkit-scrollbar {
        width: 8px;
    }
    textarea::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    textarea::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    textarea::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>