import re
import os

def fix_file(filepath):
    if not os.path.exists(filepath): return
    with open(filepath, 'r') as f:
        content = f.read()

    content = content.replace('<x-layouts.app', '<x-app-layout')
    content = content.replace('</x-layouts.app>', '</x-app-layout>')
    content = content.replace("route('support.tickets.", "route('admin.tickets.")
    content = content.replace("$ticket->partner->name", "$ticket->tenant->name")
    
    # In Tenant (admin), the super admin is just config('app.name') Team. It's already there in show.blade.php
    
    # Remove Original Description block
    desc_start = content.find("{{-- Original description --}}")
    replies_start = content.find("{{-- Replies Thread --}}")
    if desc_start != -1 and replies_start != -1:
        content = content[:desc_start] + content[replies_start:]

    # Remove Attachment tags
    content = re.sub(r'@if\(\$ticket->attachment_path\).*?@endif', '', content, flags=re.DOTALL)
    content = re.sub(r'@if\(\$reply->attachment_path\).*?@endif', '', content, flags=re.DOTALL)
    content = re.sub(r'\{\{-- File attachment --\}\}.*?</div>\s*<div class="flex flex-col sm:flex-row gap-3">', '<div class="flex flex-col sm:flex-row gap-3">', content, flags=re.DOTALL)
    content = content.replace('enctype="multipart/form-data"', '')
    
    # Fix category in index/show
    content = content.replace("support.tickets", "admin.tickets")
    content = content.replace("superadmin.support", "admin.tickets")

    with open(filepath, 'w') as f:
        f.write(content)

fix_file('resources/views/admin/tickets/index.blade.php')
fix_file('resources/views/admin/tickets/show.blade.php')
fix_file('resources/views/admin/tickets/create.blade.php')
