import urllib.request
import re

url = "https://edulab.hivetheme.com/"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
try:
    with urllib.request.urlopen(req) as response:
        html = response.read().decode('utf-8')
        
        # search for nav tag or menu
        nav_match = re.search(r'<nav[^>]*>.*?</nav>', html, re.DOTALL | re.IGNORECASE)
        if nav_match:
            print("NAV HTML FOUND (first 500 chars):")
            print(nav_match.group(0)[:500])
            print("---------------------------------")
            
        header_match = re.search(r'<header[^>]*>.*?</header>', html, re.DOTALL | re.IGNORECASE)
        if header_match:
            print("HEADER HTML FOUND (first 2000 chars):")
            print(header_match.group(0)[:2000])
        
        # look for linked css files
        css_links = re.findall(r'<link[^>]*rel=["\']stylesheet["\'][^>]*href=["\']([^"\']+)["\']', html, re.IGNORECASE)
        print("\nCSS LINKS:")
        for link in css_links:
            print(link)
except Exception as e:
    print(f"Error: {e}")
