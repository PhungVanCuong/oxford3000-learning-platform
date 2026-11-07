import glob
import csv
import json
import sys
import math
import os

levels_spec = [
    ('a1', 'Tài liệu tiếng Anh/forumflash-tu-vung-co-ban-a1.csv', 'Từ vựng cơ bản A1', 'A1', 45, '#00b074', 'Nền tảng giao tiếp cơ bản hàng ngày'),
    ('a2', 'Tài liệu tiếng Anh/forumflash-tu-vung-co-ban-a2.csv', 'Từ vựng cơ bản A2', 'A2', 40, '#06b6d4', 'Mở rộng vốn từ sơ trung cấp thực tế'),
    ('b1', 'Tài liệu tiếng Anh/forumflash-tu-vung-co-ban-b1.csv', 'Từ vựng cơ bản B1', 'B1', 34, '#f43f5e', 'Trung cấp - Đàm thoại & công việc linh hoạt'),
    ('b2', 'Tài liệu tiếng Anh/forumflash-tu-vung-co-ban-b2.csv', 'Từ vựng cơ bản B2', 'B2', 27, '#f59e0b', 'Trung cao cấp - Diễn đạt chuyên sâu & học thuật'),
    ('c1', 'Tài liệu tiếng Anh/forumflash-tu-vung-co-ban-c1.csv', 'Từ vựng cơ bản C1', 'C1', 65, '#6366f1', 'Cao cấp - IELTS 7.0+ & nghiên cứu chuyên nghiệp'),
    ('c2', 'Tài liệu tiếng Anh/forumflash-tu-vung-co-ban-c2.csv', 'Từ vựng cơ bản C2', 'C2', 36, '#ec4899', 'Thành thạo bản xứ - Tinh hoa ngôn ngữ đỉnh cao'),
]

os.makedirs('frontend/src/data/cefr', exist_ok=True)

manifest = {
    'levels': []
}

total_words = 0
total_lessons = 0

for level_id, file_path, title, code, num_lessons, color, desc in levels_spec:
    words = []
    with open(file_path, mode='r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        for idx, row in enumerate(reader):
            term = row.get('Term') or row.get('\ufeffTerm') or row.get('\ufeff"Term"') or ''
            if not term:
                for k in row.keys():
                    if 'term' in k.lower():
                        term = row[k]
                        break
            term = term.strip(' "\'')
            definition = (row.get('Definition') or '').strip()
            pos = (row.get('Part of speech') or '').strip()
            pattern = (row.get('Pattern') or '').strip()
            examples = (row.get('Examples') or '').strip()
            
            ex_en = ''
            ex_vi = ''
            if '|' in examples:
                parts = [p.strip() for p in examples.split('|')]
                ex_en = parts[0]
                ex_vi = parts[1] if len(parts) > 1 else ''
            else:
                ex_en = examples
            
            words.append({
                'id': f'{level_id}_{idx+1}',
                'term': term,
                'definition': definition,
                'meaning_vi': definition,
                'part_of_speech': pos,
                'pattern': pattern,
                'example_en': ex_en,
                'example_vi': ex_vi,
                'level_code': code,
            })
    
    total_words += len(words)
    total_lessons += num_lessons
    
    words_per_lesson = math.ceil(len(words) / num_lessons)
    lessons = []
    for l_idx in range(num_lessons):
        start = l_idx * words_per_lesson
        end = min(len(words), (l_idx + 1) * words_per_lesson)
        lesson_words = words[start:end]
        if not lesson_words and words:
            lesson_words = [words[-1]]
        lessons.append({
            'lesson_number': l_idx + 1,
            'title': f'Bài {l_idx + 1:02d}',
            'word_count': len(lesson_words),
            'words': lesson_words
        })
    
    level_data = {
        'id': level_id,
        'code': code,
        'title': title,
        'description': desc,
        'color': color,
        'total_words': len(words),
        'total_lessons': num_lessons,
        'lessons': lessons
    }

    # Write individual level json for instant lazy loading
    with open(f'frontend/src/data/cefr/{level_id}.json', 'w', encoding='utf-8') as f:
        json.dump(level_data, f, ensure_ascii=False)
    
    # Add to manifest (without heavy word payload, for fast navigation)
    manifest['levels'].append({
        'id': level_id,
        'code': code,
        'title': title,
        'description': desc,
        'color': color,
        'total_words': len(words),
        'total_lessons': num_lessons,
        'lesson_summaries': [
            {'lesson_number': l['lesson_number'], 'title': l['title'], 'word_count': l['word_count']}
            for l in lessons
        ]
    })

with open('frontend/src/data/cefr/manifest.json', 'w', encoding='utf-8') as f:
    json.dump(manifest, f, ensure_ascii=False, indent=2)

print(f'Generated CEFR manifest + individual level JSONs! Total: {total_words} words, {total_lessons} lessons.')
