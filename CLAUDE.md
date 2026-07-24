# Laravel Development Guidelines

## 🚀 Tech Stack & Versions
- **PHP:** 8.2.29
- **Laravel:** v12 (streamlined structure)
- **Livewire:** v3 + Volt v1
- **Flux UI:** v2 (Free edition)
- **Pest:** v3 testing framework
- **Tailwind CSS:** v4
- **Pint:** v1 code formatter

## 🎯 Core Principles

### File Creation & Structure
- **Follow existing conventions** - Check sibling files for structure, approach, naming
- **Use Artisan commands** for all file creation: `php artisan make:* --no-interaction`
- **Stick to existing directory structure** - No new base folders without approval
- **Reuse before creating** - Check for existing components first
- **No documentation files** unless explicitly requested

### Code Standards
- **Descriptive naming**: `isRegisteredForDiscounts()` not `discount()`
- **PHP 8 constructor promotion**: `public function __construct(public GitHub $github) {}`
- **Explicit return types**: All methods must have return type declarations
- **Always use curly braces** for control structures (even single line)
- **PHPDoc over inline comments** - Only comment complex logic
- **Enum keys in TitleCase**: `FavoritePerson`, `BestLake`

## 🔧 Laravel Boost Tools (Use These First!)

### Essential Tools
```php
// Documentation search (CRITICAL - use before any other approach)
search-docs(['authentication', 'middleware']) // Version-specific docs

// Database & debugging
tinker('User::latest()->take(5)->get()') // Execute PHP
database-query('SELECT * FROM users LIMIT 5') // Read-only queries
browser-logs(10) // Recent frontend errors

// Artisan & URLs  
list-artisan-commands() // Check available commands
get-absolute-url('/dashboard') // Correct project URLs
```

### Documentation Search Rules
- **Always use `search-docs` before coding** - Returns version-specific documentation
- **Multiple simple queries**: `['rate limiting', 'routing', 'middleware']`
- **No package names in queries** - Package info is auto-included
- **Use quoted phrases** for exact matches: `"infinite scroll"`

## 📝 Laravel Best Practices

### Database & Models
```php
// ✅ Good: Eloquent relationships with types
public function posts(): HasMany
{
    return $this->hasMany(Post::class);
}

// ✅ Good: Prevent N+1 queries
$users = User::with('posts', 'comments')->get();

// ✅ Good: Model scopes
public function scopeActive($query): Builder
{
    return $query->where('status', 'active');
}

// ❌ Avoid: DB:: facade - use Model::query() instead
```

### Controllers & Validation
```php
// ✅ Always use Form Request classes
public function store(StoreUserRequest $request): Response
{
    // Clean controller logic
}

// ✅ Form Request validation
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return ['name' => 'required|string|max:255'];
    }
}
```

### Configuration & Environment
```php
// ✅ Good: Use config files
$apiKey = config('app.api_key');

// ❌ Bad: Direct env() usage outside config
$apiKey = env('API_KEY'); // Only in config files!
```

### Laravel 12 Specifics
- **No `app/Http/Middleware/`** - Register in `bootstrap/app.php`
- **No `app/Console/Kernel.php`** - Commands auto-register
- **Service providers** in `bootstrap/providers.php`
- **Column modifications** must include all previous attributes

## 🎨 Frontend Stack

### Livewire 3 + Volt
```php
// Volt class-based component
use Livewire\Volt\Component;

new class extends Component {
    public $count = 0;
    
    public function increment(): void 
    {
        $this->count++;
    }
} ?>

<div>
    <h1>{{ $count }}</h1>
    <flux:button wire:click="increment">+</flux:button>
</div>
```

### Key Livewire 3 Changes
- **Real-time**: `wire:model.live` (not just `wire:model`)
- **Namespace**: `App\Livewire` (not `App\Http\Livewire`)
- **Events**: `$this->dispatch()` (not `emit`)
- **Loading states**: `wire:loading`, `wire:dirty`
- **Loop keys**: Always use `wire:key="item-{{ $item->id }}"`

### Flux UI Components
```blade
{{-- Available components --}}
<flux:button variant="primary">Save</flux:button>
<flux:input wire:model.live="search" />
<flux:modal>Content</flux:modal>

{{-- Available: avatar, badge, breadcrumbs, callout, checkbox, dropdown, 
     field, heading, icon, navbar, profile, radio, select, separator, 
     switch, text, textarea, tooltip --}}
```

### Tailwind CSS v4
```css
/* ✅ New import syntax */
@import "tailwindcss";

/* ❌ Old v3 syntax - don't use */
@tailwind base;
@tailwind components; 
@tailwind utilities;
```

**Updated Utilities:**
- `bg-opacity-*` → `bg-black/*`
- `text-opacity-*` → `text-black/*`  
- `flex-shrink-*` → `shrink-*`
- `overflow-ellipsis` → `text-ellipsis`

## 🧪 Testing with Pest

### Test Creation & Structure
```php
// Create tests
php artisan make:test --pest UserTest        // Feature test
php artisan make:test --pest --unit UserTest // Unit test

// Basic test structure
it('creates user successfully', function () {
    $user = User::factory()->create();
    expect($user)->toBeInstanceOf(User::class);
});

// Livewire/Volt testing
test('counter increments', function () {
    Volt::test('counter')
        ->assertSee('Count: 0')
        ->call('increment')
        ->assertSee('Count: 1');
});
```

### Test Execution
```bash
php artisan test                              # All tests
php artisan test tests/Feature/UserTest.php  # Specific file
php artisan test --filter=testName           # Specific test
```

### Assertions
```php
// ✅ Use specific assertions
$response->assertSuccessful();
$response->assertForbidden();

// ❌ Avoid generic status codes
$response->assertStatus(200);
```

## 🏗️ Architecture Patterns

### Error Handling
```php
public function processData(): array
{
    try {
        // Main logic
        return $this->process();
    } catch (ValidationException $e) {
        // Handle validation errors
        Log::error('Validation failed', ['error' => $e->getMessage()]);
        throw $e;
    } catch (\Exception $e) {
        // Handle general errors
        Log::error('Processing failed', ['error' => $e->getMessage()]);
        throw $e;
    }
}
```

### Database Transactions
```php
DB::beginTransaction();
try {
    $user = User::create($userData);
    $profile = Profile::create($profileData);
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    throw $e;
}
```

### Queue Jobs
```php
class ProcessPayment implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable;
    
    public function handle(): void
    {
        // Time-consuming logic here
    }
}
```

## 🔧 Development Workflow

### Code Quality Commands
```bash
# Format code (run before committing)
vendor/bin/pint --dirty

# Run tests after changes  
php artisan test --filter=RelatedTest

# Frontend compilation
npm run dev    # Development
npm run build  # Production
```

### Common Issues
- **Vite manifest error**: Run `npm run build` or ask user to run `npm run dev`
- **Frontend changes not showing**: User needs to run build commands
- **Tests over verification scripts**: Write tests instead of tinker scripts

## 📋 Quick Reference

### Must Do
- ✅ Use Boost tools (`search-docs`, `tinker`, etc.)
- ✅ Follow existing code conventions
- ✅ Create Form Request classes for validation
- ✅ Use Eloquent relationships with type hints
- ✅ Write Pest tests for all changes
- ✅ Run Pint before committing

### Never Do
- ❌ Use `env()` outside config files
- ❌ Create new base directories without approval
- ❌ Use `DB::` instead of Model queries
- ❌ Create documentation files unless requested
- ❌ Skip tests for new functionality
- ❌ Use deprecated Tailwind utilities

This optimized guide focuses on the essential rules while maintaining all critical information in a much more readable and actionable format.