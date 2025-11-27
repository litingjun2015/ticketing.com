#!/usr/bin/env python3
import re

# 读取文件
with open('pages.json', 'r', encoding='utf-8') as f:
    content = f.read()

# 移除第5行的注释
content = content.replace(
    '\t"pages": [ //pages数组中第一项表示应用启动页，参考：https://uniapp.dcloud.io/collocation/pages',
    '\t"pages": ['
)

# 移除文件末尾损坏的部分并重建
# 找到最后一个完整的 pagesC 分包结束位置
lines = content.split('\n')
new_lines = []
found_join = False

for i, line in enumerate(lines):
    new_lines.append(line)
    if '"path": "join/join"' in line:
        found_join = True
    if found_join and '"navigationBarTitleText": "景区入驻"' in line:
        # 找到了，添加剩余部分
        new_lines.append('\t\t\t\t}')
        new_lines.append('\t\t\t}]')
        new_lines.append('\t\t}')
        new_lines.append('')
        new_lines.append('\t],')
        new_lines.append('\t"globalStyle": {')
        new_lines.append('\t\t"navigationBarTextStyle": "black",')
        new_lines.append('\t\t"navigationBarTitleText": "uni-app",')
        new_lines.append('\t\t"navigationBarBackgroundColor": "#F8F8F8",')
        new_lines.append('\t\t"backgroundColor": "#F8F9FC"')
        new_lines.append('\t},')
        new_lines.append('\t"uniIdRouter": {}')
        new_lines.append('}')
        break

# 写回文件
with open('pages.json', 'w', encoding='utf-8') as f:
    f.write('\n'.join(new_lines))

print("pages.json 已修复")
