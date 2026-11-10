import glob
import csv
import json
import sys
import math
import os

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
PROJECT_ROOT = os.path.dirname(os.path.dirname(SCRIPT_DIR))

levels_spec = [
    ('a1', os.path.join(PROJECT_ROOT, 'Tài liệu tiếng Anh', 'forumflash-tu-vung-co-ban-a1.csv'), 'Từ vựng cơ bản A1', 'A1', 45, '#00b074', 'Nền tảng giao tiếp cơ bản hàng ngày'),
    ('a2', os.path.join(PROJECT_ROOT, 'Tài liệu tiếng Anh', 'forumflash-tu-vung-co-ban-a2.csv'), 'Từ vựng cơ bản A2', 'A2', 40, '#06b6d4', 'Mở rộng vốn từ sơ trung cấp thực tế'),
    ('b1', os.path.join(PROJECT_ROOT, 'Tài liệu tiếng Anh', 'forumflash-tu-vung-co-ban-b1.csv'), 'Từ vựng cơ bản B1', 'B1', 34, '#f43f5e', 'Trung cấp - Đàm thoại & công việc linh hoạt'),
    ('b2', os.path.join(PROJECT_ROOT, 'Tài liệu tiếng Anh', 'forumflash-tu-vung-co-ban-b2.csv'), 'Từ vựng cơ bản B2', 'B2', 27, '#f59e0b', 'Trung cao cấp - Diễn đạt chuyên sâu & học thuật'),
    ('c1', os.path.join(PROJECT_ROOT, 'Tài liệu tiếng Anh', 'forumflash-tu-vung-co-ban-c1.csv'), 'Từ vựng cơ bản C1', 'C1', 65, '#6366f1', 'Cao cấp - IELTS 7.0+ & nghiên cứu chuyên nghiệp'),
    ('c2', os.path.join(PROJECT_ROOT, 'Tài liệu tiếng Anh', 'forumflash-tu-vung-co-ban-c2.csv'), 'Từ vựng cơ bản C2', 'C2', 36, '#ec4899', 'Thành thạo bản xứ - Tinh hoa ngôn ngữ đỉnh cao'),
]

output_dir = os.path.join(PROJECT_ROOT, 'frontend', 'src', 'data', 'cefr')
os.makedirs(output_dir, exist_ok=True)

manifest = {
    'levels': []
}

total_words = 0
total_lessons = 0

def generate_lesson_counts(total_words, num_lessons, start_count=12, end_count=27):
    # Generates monotonic non-decreasing integers from start_count to end_count summing to total_words
    counts = []
    for i in range(num_lessons):
        val = start_count + (end_count - start_count) * (i / (num_lessons - 1))
        counts.append(int(round(val)))
    
    # Adjust difference across middle lessons
    diff = total_words - sum(counts)
    if diff != 0:
        step = 1 if diff > 0 else -1
        indices = [int(round(1 + i * (num_lessons - 3) / abs(diff))) for i in range(abs(diff))]
        for idx in indices:
            counts[min(num_lessons - 2, max(1, idx))] += step
            
    if counts[0] != 12: 
        counts[0] = 12
    if num_lessons > 1 and counts[1] <= 12: 
        counts[1] = 13
        counts[num_lessons // 2] -= 1
        
    # Sort to ensure smooth non-decreasing progression
    counts.sort()
    # Guarantee first is 12, second is 13
    counts[0] = 12
    counts[1] = 13
    diff_final = total_words - sum(counts)
    counts[-1] += diff_final
    return counts

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
    
    end_c = 28 if level_id in ('c1', 'c2') else 27
    counts = generate_lesson_counts(len(words), num_lessons, 12, end_c)
    
    lessons = []
    curr_offset = 0
    for l_idx, count in enumerate(counts):
        lesson_words = words[curr_offset : curr_offset + count]
        curr_offset += count
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
    with open(os.path.join(output_dir, f'{level_id}.json'), 'w', encoding='utf-8') as f:
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

with open(os.path.join(output_dir, 'manifest.json'), 'w', encoding='utf-8') as f:
    json.dump(manifest, f, ensure_ascii=False, indent=2)

print(f'Generated CEFR manifest + individual level JSONs! Total: {total_words} words, {total_lessons} lessons.')
