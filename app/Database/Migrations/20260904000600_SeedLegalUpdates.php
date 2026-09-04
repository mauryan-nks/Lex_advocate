<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;
class SeedLegalUpdates extends Migration
{
    public function up(): void
    {
        $db = db_connect();
        if ((int)$db->table('legal_updates')->countAllResults() > 0) return;
        $now = date('Y-m-d H:i:s');
        $rows = [
            ['title'=>'Understanding the Bharatiya Nyaya Sanhita (BNS), 2023','slug'=>'bns-2023-explained','excerpt'=>'The Bharatiya Nyaya Sanhita, 2023 is the principal new substantive criminal law replacing the Indian Penal Code framework, with the notified provisions coming into force from 1 July 2024, except the specified provision of section 106(2).','content'=>'<h2>Understanding the BNS, 2023</h2><p>The Bharatiya Nyaya Sanhita, 2023 is the principal new substantive criminal law replacing the Indian Penal Code framework. The content on this website is provided for general information and should be read with the official statutory text and notifications.</p><p>The notified provisions came into force from 1 July 2024, except the specified provision of section 106(2).</p>','category'=>'BNS Explained','status'=>'published','published_at'=>$now,'created_at'=>$now,'updated_at'=>$now],
            ['title'=>'BNS, BNSS and BSA: What changed?','slug'=>'bns-bnss-bsa-what-changed','excerpt'=>'A practical overview of the three new criminal-law statutes and the difference between substantive criminal law, procedure and evidence.','content'=>'<h2>Three related statutes</h2><p>BNS addresses substantive criminal offences, BNSS addresses criminal procedure, and BSA addresses the law of evidence. Always verify the current statutory text and notifications when applying these provisions.</p>','category'=>'New Criminal Laws','status'=>'published','published_at'=>$now,'created_at'=>$now,'updated_at'=>$now],
            ['title'=>'How to read a new Bill before it becomes law','slug'=>'how-to-read-a-new-bill','excerpt'=>'A public-information guide to tracking a Bill, understanding amendments, and distinguishing a proposal from an enacted law.','content'=>'<h2>From Bill to law</h2><p>A Bill is a legislative proposal. Track its official status, versions, amendments and eventual enactment rather than treating a proposal as law.</p>','category'=>'New Bill','status'=>'published','published_at'=>$now,'created_at'=>$now,'updated_at'=>$now],
            ['title'=>'What a legal update should tell you','slug'=>'what-a-legal-update-should-tell-you','excerpt'=>'Key questions: what changed, when it applies, whom it affects, and where the official text can be checked.','content'=>'<h2>Reading legal updates</h2><p>A useful legal update should identify what changed, when it applies, who may be affected and where the authoritative text can be checked.</p>','category'=>'Legal Guide','status'=>'published','published_at'=>$now,'created_at'=>$now,'updated_at'=>$now],
        ];
        $db->table('legal_updates')->insertBatch($rows);
    }
    public function down(): void
    {
        db_connect()->table('legal_updates')->whereIn('slug',['bns-2023-explained','bns-bnss-bsa-what-changed','how-to-read-a-new-bill','what-a-legal-update-should-tell-you'])->delete();
    }
}
