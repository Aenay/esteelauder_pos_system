# jQuery Setup Instructions

This project is configured to use both CDN and local jQuery files for maximum reliability.

## Current Setup

### 1. CDN jQuery (Primary)
- **Version**: jQuery 3.7.1
- **CDN**: https://code.jquery.com/jquery-3.7.1.min.js
- **Integrity**: sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=
- **Location**: Automatically loaded in `resources/views/layouts/app.blade.php`

### 2. Local jQuery (Fallback)
- **Version**: jQuery 3.7.1
- **Location**: `public/js/jquery-3.7.1.min.js`
- **Fallback**: Automatically loads if CDN fails

## How to Download and Setup Local jQuery

### Option 1: Manual Download
1. Visit https://jquery.com/download/
2. Download jQuery 3.7.1 (minified version)
3. Save the file as `jquery-3.7.1.min.js` in the `public/js/` directory

### Option 2: Using npm (Recommended)
```bash
npm install
```
This will install jQuery locally in `node_modules/jquery/dist/jquery.min.js`

Then copy it to the public directory:
```bash
# Windows
copy node_modules\jquery\dist\jquery.min.js public\js\jquery-3.7.1.min.js

# Linux/Mac
cp node_modules/jquery/dist/jquery.min.js public/js/jquery-3.7.1.min.js
```

### Option 3: Direct Download Command
```bash
# Windows PowerShell
Invoke-WebRequest -Uri "https://code.jquery.com/jquery-3.7.1.min.js" -OutFile "public\js\jquery-3.7.1.min.js"

# Linux/Mac
curl -o public/js/jquery-3.7.1.min.js https://code.jquery.com/jquery-3.7.1.min.js
```

## How It Works

The layout file (`resources/views/layouts/app.blade.php`) includes:

1. **Primary CDN Load**: Loads jQuery from CDN with integrity check
2. **Fallback Detection**: Checks if jQuery loaded successfully
3. **Local Fallback**: If CDN fails, automatically loads local file

```html
<!-- jQuery CDN (Primary) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- jQuery Local Fallback (if CDN fails) -->
<script>
    window.jQuery || document.write('<script src="{{ asset('js/jquery-3.7.1.min.js') }}"><\/script>');
</script>
```

## Usage in Your Views

jQuery is now available globally in all your Blade templates. You can use it in:

1. **Inline scripts**:
```html
<script>
$(document).ready(function() {
    // Your jQuery code here
    console.log('jQuery version:', $.fn.jquery);
});
</script>
```

2. **External JS files** (loaded via Vite):
```javascript
// In resources/js/app.js or any Vite-loaded file
$(document).ready(function() {
    // Your jQuery code here
});
```

3. **Component files**:
```html
<!-- In any Blade component -->
<script>
$(document).ready(function() {
    $('#myElement').click(function() {
        alert('jQuery is working!');
    });
});
</script>
```

## Verification

To verify jQuery is working, open browser console and run:
```javascript
console.log('jQuery version:', $.fn.jquery);
console.log('jQuery loaded from:', window.jQuery ? 'CDN or Local' : 'Not loaded');
```

## Benefits of This Setup

1. **Reliability**: CDN provides fast loading, local file provides backup
2. **Offline Support**: Local file works when internet is unavailable
3. **Security**: Integrity check ensures CDN file hasn't been tampered with
4. **Performance**: CDN is cached globally, local file is cached locally
5. **Flexibility**: Easy to switch between CDN and local-only if needed

## Troubleshooting

- If jQuery doesn't load, check browser console for errors
- Ensure the local file exists at `public/js/jquery-3.7.1.min.js`
- Verify file permissions allow web server to read the file
- Check that the CDN URL is accessible from your network
