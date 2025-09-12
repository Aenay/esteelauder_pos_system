# jQuery Setup Instructions

This project is configured to use **local jQuery only** for offline reliability and faster loading.

## Current Setup

### Local jQuery (Primary)
- **Version**: jQuery 3.7.1
- **Location**: `public/js/jquery-3.7.1.min.js`
- **Size**: 87,533 bytes
- **Status**: ✅ Downloaded and ready to use
- **Loading**: Automatically loaded in `resources/views/layouts/app.blade.php`

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

1. **Local jQuery Load**: Loads jQuery directly from the local file
2. **No Internet Required**: Works completely offline
3. **Faster Loading**: No external network requests needed

```html
<!-- jQuery Local (Manual Installation) -->
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
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

1. **Offline Support**: Works completely without internet connection
2. **Faster Loading**: No external network requests needed
3. **Reliability**: No dependency on external CDN availability
4. **Security**: No external scripts loaded from third parties
5. **Performance**: Local file loads instantly from your server
6. **Control**: You have complete control over the jQuery version

## Troubleshooting

- If jQuery doesn't load, check browser console for errors
- Ensure the local file exists at `public/js/jquery-3.7.1.min.js`
- Verify file permissions allow web server to read the file
- Check that the file is accessible via `http://yoursite.com/js/jquery-3.7.1.min.js`
- Clear browser cache if you recently updated the jQuery file
