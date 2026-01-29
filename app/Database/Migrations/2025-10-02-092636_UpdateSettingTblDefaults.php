<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSettingTblDefaults extends Migration
{
    public function up()
    {
        //
        $this->db->query("ALTER TABLE public.setting_tbl ALTER COLUMN site_address DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.setting_tbl ALTER COLUMN email_address DROP NOT NULL;");
        $this->db->query("ALTER TABLE public.setting_tbl ALTER COLUMN modification_date DROP NOT NULL;");
        
    }

    public function down()
    {
        //
        $this->db->query("ALTER TABLE public.setting_tbl ALTER COLUMN site_address SET NOT NULL;");
        $this->db->query("ALTER TABLE public.setting_tbl ALTER COLUMN email_address SET NOT NULL;");
        $this->db->query("ALTER TABLE public.setting_tbl ALTER COLUMN modification_date SET NOT NULL;");
    }
}
