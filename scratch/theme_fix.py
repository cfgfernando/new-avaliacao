import os
import re

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Replace text-white but not text-white/10 etc.
    new_content = re.sub(r'text-white(?!/)', 'text-text-title', content)
    # Replace bg-white
    new_content = re.sub(r'bg-white', 'bg-text-title', new_content)
    
    if new_content != content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath}")

root_dir = 'resources/views'
for root, dirs, files in os.walk(root_dir):
    for file in files:
        if file.endswith('.blade.php'):
            replace_in_file(os.path.join(root, file))
