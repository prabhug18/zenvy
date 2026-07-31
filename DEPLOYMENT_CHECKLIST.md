# Laravel Deployment Checklist & Troubleshooting Guide

This guide covers critical steps to ensure your Laravel application (Zenvy LMS) works perfectly when deployed to a live production server. It specifically addresses common issues regarding missing images, broken icons, and mixed-content blocking.

---

## 1. The `storage` Symlink (Fixes Missing Uploaded Images)

### The Problem
Images uploaded via the admin panel (like course hero images, organization logos, and user avatars) are saved in the `storage/app/public` folder. By default, browsers cannot access the `storage` directory directly. 

Laravel uses a "shortcut" (symlink) located at `public/storage` to grant public access to these files. However, this symlink is ignored by Git (`.gitignore`), meaning **it will not be uploaded to your live server automatically**. If it is missing, all uploaded images will return a `404 Not Found` error.

### The Fix
Whenever you set up the project on a new live server, you **must** create this symlink. 

**If you have SSH (Terminal) access to the server:**
Run this command from your project root:
```bash
php artisan storage:link
```

**If you use Shared Hosting (cPanel/Plesk) with no terminal:**
You can temporarily create a route to run the command for you. Add this to your `routes/web.php`:
```php
Route::get('/create-symlink', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Symlink Created Successfully!';
});
```
Visit `https://yourdomain.com/create-symlink` in your browser once, and then immediately remove the route for security.

---

## 2. SSL and Mixed Content (Fixes Broken Icons & Styles)

### The Problem
If your site is loaded securely over `https://`, but Laravel thinks it is running on `http://`, it will generate insecure URLs for your CSS, fonts, and icons. Modern browsers (especially Safari on Mac/iOS) enforce strict security and will completely block these insecure "mixed content" files, causing your icons (like the Edit/Delete boxes) to appear as empty squares.

### The Fix
1. **Update your `.env` file** on the live server. Ensure the following variables are strictly set:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ASSET_URL=https://yourdomain.com
   ```
2. **Clear the cache** so the new `.env` rules take effect:
   ```bash
   php artisan config:cache
   php artisan view:clear
   php artisan cache:clear
   ```
*(Note: I have already updated `AppServiceProvider.php` to automatically force HTTPS for assets when `APP_ENV=production` is set, which acts as a strong safeguard against this issue).*

---

## 3. Font MIME Types (Only if hosting on Windows / IIS)

### The Problem
If your live server uses Windows/IIS instead of Linux/Apache/Nginx, the server does not recognize modern font files (`.woff` and `.woff2`) out of the box. Instead of sending the font to the browser, IIS will block it and return a `404 Not Found` error, which completely breaks all Remix Icons in the admin panel.

### The Fix
If you are using a Windows Server, you need to tell IIS how to handle font files. 
Create a file named `web.config` inside your `public` directory, and paste the following XML configuration:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <staticContent>
            <!-- Clear any existing types to prevent crashes -->
            <remove fileExtension=".woff" />
            <remove fileExtension=".woff2" />
            <!-- Add the proper font MIME types -->
            <mimeMap fileExtension=".woff" mimeType="application/font-woff" />
            <mimeMap fileExtension=".woff2" mimeType="application/font-woff2" />
        </staticContent>
    </system.webServer>
</configuration>
```

---

## Pre-Flight Checklist Before Client Handoff

Before handing the live site to the client, verify the following:
- [ ] `.env` is set to `APP_ENV=production` and `APP_DEBUG=false`.
- [ ] `.env` `APP_URL` starts with `https://`.
- [ ] `php artisan storage:link` has been successfully executed.
- [ ] Cached config has been cleared using `php artisan optimize:clear`.
- [ ] Test the site on a mobile device and an incognito window to ensure no assets are blocked.
