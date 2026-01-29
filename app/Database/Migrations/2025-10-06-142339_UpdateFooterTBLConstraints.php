<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFooterTBLConstraints extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE public.footer_tbl ALTER COLUMN modified_date DROP NOT NULL;");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE public.footer_tbl ALTER COLUMN modified_date SET NOT NULL;");
    }
}
