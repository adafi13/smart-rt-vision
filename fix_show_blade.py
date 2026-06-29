import re

with open('resources/views/super-admin/tickets/show.blade.php', 'r') as f:
    content = f.read()

content = content.replace('<x-layouts.app', '<x-super-admin-layout')
content = content.replace('</x-layouts.app>', '</x-super-admin-layout>')
content = content.replace("route('superadmin.support.index')", "route('super-admin.tickets.index')")
content = content.replace("route('superadmin.support.reply', $ticket)", "route('super-admin.tickets.reply', $ticket)")
content = content.replace("route('superadmin.support.status', $ticket)", "route('super-admin.tickets.status', $ticket)")
content = content.replace("route('superadmin.support.assign', $ticket)", "route('super-admin.tickets.assign', $ticket)")
content = content.replace("$ticket->partner->name", "$ticket->tenant->name")
content = content.replace("Mitra", "Tenant")
content = content.replace("mitra", "tenant")

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

with open('resources/views/super-admin/tickets/show.blade.php', 'w') as f:
    f.write(content)

