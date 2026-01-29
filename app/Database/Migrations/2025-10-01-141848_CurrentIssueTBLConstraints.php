<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CurrentIssueTBLConstraints extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE public.current_issue_tbl ALTER COLUMN page DROP NOT NULL;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE public.current_issue_tbl ALTER COLUMN page SET NOT NULL;");
    }
}
