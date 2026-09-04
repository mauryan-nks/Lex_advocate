<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPracticeBuilder extends Migration
{
    public function up(): void
    {
        if (! $this->db->fieldExists('builder_json', 'practice_areas')) {
            $this->forge->addColumn('practice_areas', [
                'builder_json' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                    'after' => 'content',
                ],
            ]);
        }

        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 160],
            'section_type' => ['type' => 'VARCHAR', 'constraint' => 40],
            'data' => ['type' => 'LONGTEXT'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['section_type']);
        $this->forge->createTable('cms_section_templates', true);

        $templates = [
            ['name'=>'Dark Process — 3 Columns','section_type'=>'process','data'=>json_encode([
                'eyebrow'=>'How a matter is approached','title'=>'A practical, structured view.','theme'=>'dark',
                'items'=>[
                    ['label'=>'01 / Context','title'=>'Understanding the matter','text'=>'Start with the facts, documents and procedural position.'],
                    ['label'=>'02 / Context','title'=>'Preparing the record','text'=>'Organise chronology, evidence and the issues that need attention.'],
                    ['label'=>'03 / Context','title'=>'Choosing the next step','text'=>'The next step depends on the facts, applicable law and competent court.'],
                ],
            ], JSON_UNESCAPED_SLASHES)],
            ['name'=>'Light Introduction','section_type'=>'richtext','data'=>json_encode([
                'eyebrow'=>'Overview','title'=>'Clear advice begins with a clear factual picture.','content'=>'<p>Add the practice-area overview here.</p>','theme'=>'light'
            ], JSON_UNESCAPED_SLASHES)],
            ['name'=>'Quote / Callout','section_type'=>'quote','data'=>json_encode([
                'label'=>'LEX FACTUM','quote'=>'The right next step starts with understanding the right facts.','text'=>'Specific outcomes depend on the facts, evidence, procedure and applicable law.','theme'=>'light'
            ], JSON_UNESCAPED_SLASHES)],
            ['name'=>'Image + Text','section_type'=>'image_text','data'=>json_encode([
                'eyebrow'=>'Related considerations','title'=>'Preparation matters as much as presentation.','content'=>'<p>Add supporting information here.</p>','image'=>'','side'=>'right','theme'=>'dark'
            ], JSON_UNESCAPED_SLASHES)],
            ['name'=>'Gold CTA','section_type'=>'cta','data'=>json_encode([
                'eyebrow'=>'NEXT STEP','title'=>'Request an initial appointment.','content'=>'<p>Share the relevant context and documents for an initial discussion.</p>','button_text'=>'Request Appointment ↗','button_url'=>'appointment','theme'=>'dark'
            ], JSON_UNESCAPED_SLASHES)],
        ];
        // Convert existing static practice-area HTML into reusable builder sections once.
        $practiceBuilder = $this->db->table('practice_areas');
        foreach ($practiceBuilder->get()->getResultArray() as $row) {
            if (!empty($row['builder_json'])) continue;
            $content = (string)($row['content'] ?? '');
            $sections = [];
            if ($content !== '' && preg_match_all('/<section\b[^>]*>(.*?)<\/section>/is', $content, $matches)) {
                foreach (($matches[1] ?? []) as $i => $inner) {
                    $sections[] = ['id'=>'legacy_'.($i+1).'_'.bin2hex(random_bytes(4)), 'type'=>'html', 'hidden'=>false, 'data'=>['theme'=>$i===1?'dark':'light','content'=>$inner]];
                }
            }
            if (!$sections && $content !== '') {
                $sections[] = ['id'=>'legacy_'.bin2hex(random_bytes(4)), 'type'=>'html', 'hidden'=>false, 'data'=>['theme'=>'light','content'=>$content]];
            }
            if ($sections) $practiceBuilder->where('id', $row['id'])->update(['builder_json'=>json_encode($sections, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)]);
        }

        $builder = $this->db->table('cms_section_templates');
        foreach ($templates as $template) {
            if (! $builder->where('name', $template['name'])->where('section_type', $template['section_type'])->get()->getRowArray()) {
                $template['created_at'] = date('Y-m-d H:i:s');
                $template['updated_at'] = date('Y-m-d H:i:s');
                $builder->insert($template);
            }
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('cms_section_templates', true);
        if ($this->db->fieldExists('builder_json', 'practice_areas')) {
            $this->forge->dropColumn('practice_areas', 'builder_json');
        }
    }
}
