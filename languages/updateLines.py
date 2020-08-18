'''
一个简易的修改行号脚本，当前只添加options.php的行号修改，start是修改起始行，行号大于这个值才会被修改，修改规则是将匹配到的行号值加上offset。
'''

import re

update_files = ['en_US.po', 'zh_CN.po']

update_content = 'options.php'

start = 218
offset = 7

for i in update_files:
    mod_lines = []
    with open(i, 'r', encoding='utf-8') as f:
        for line in f:
            items = line.split(' ')
            mod_items = []
            for item in items:
                cur = re.findall(r'%s:(\d+)' % update_content, item)
                if cur:
                    before = int(cur[0])
                    after = (before + offset) if before > start else before
                    item = re.sub('%s:%d' % (update_content, before), '%s:%d' % (update_content, after), item)
                    print(before, item)
                mod_items.append(item)
            mod_lines.append(' '.join(mod_items))
    print(mod_lines)

    with open(i, 'w+', encoding='utf-8') as f:
        for line in mod_lines:
            f.write(line)
